<?php

namespace App\Services;

class YouTubePlaylistService
{
    /**
     * Extract playlist ID from various URL formats or raw ID.
     */
    public static function extractPlaylistId(string $input): ?string
    {
        $input = trim($input);

        // Standard YouTube playlist URL: ...list=PL...
        if (preg_match('/[?&]list=([a-zA-Z0-9_-]+)/i', $input, $matches)) {
            return $matches[1];
        }

        // Direct /playlist/PL... or similar
        if (preg_match('/\/playlist\/([a-zA-Z0-9_-]+)/i', $input, $matches)) {
            return $matches[1];
        }

        // Raw playlist ID (starts with PL, UU, FL, RD, OLAK, etc.)
        if (preg_match('/^[a-zA-Z0-9_-]{10,}$/', $input)) {
            return $input;
        }

        return null;
    }

    /**
     * Extract YouTube video ID from URL, iframe code, or raw ID.
     */
    public static function extractVideoId(string $input): ?string
    {
        $input = trim($input);

        // 1. iframe tag: <iframe src="...youtube.com/embed/VIDEO_ID...">
        if (preg_match('/<iframe[^>]*\ssrc=["\'](?:https?:)?\/\/(?:www\.)?youtube(?:-nocookie)?\.com\/embed\/([a-zA-Z0-9_-]{11})/i', $input, $m)) {
            return $m[1];
        }

        // 2. watch?v=VIDEO_ID or &v=VIDEO_ID
        if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/i', $input, $m)) {
            return $m[1];
        }

        // 3. youtu.be/VIDEO_ID or embed/VIDEO_ID or /v/VIDEO_ID or /shorts/VIDEO_ID
        if (preg_match('/(?:youtu\.be\/|embed\/|\/v\/|\/shorts\/)([a-zA-Z0-9_-]{11})/i', $input, $m)) {
            return $m[1];
        }

        // 4. Raw 11-char ID
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }

        return null;
    }

    /**
     * Fetch playlist title and all video lessons from YouTube using multi-tier fallback.
     *
     * @param string $playlistId
     * @return array{success: bool, title: string, count: int, videos: array, message: ?string}
     */
    public function fetchPlaylist(string $playlistId): array
    {
        $playlistTitle = 'YouTube Imported Course Series';
        $videos = [];

        // -------------------------------------------------------------
        // STRATEGY 1: Desktop HTML + ytInitialData extraction
        // -------------------------------------------------------------
        $url = 'https://www.youtube.com/playlist?list=' . urlencode($playlistId) . '&hl=en';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            CURLOPT_HTTPHEADER => [
                'Accept-Language: en-US,en;q=0.9',
                'Cookie: SOCS=CAESEwgDEgk2OTk4OTAzMTAaAmVuIAEaBgiA_LyaBg; CONSENT=YES+cb'
            ],
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $html = curl_exec($ch);
        curl_close($ch);

        $alertMessage = null;

        if ($html && strlen($html) > 500) {
            $data = $this->extractJsonFromHtml($html);
            if ($data) {
                // Check if YouTube gave an error alert (e.g. Playlist does not exist or private)
                if (!empty($data['alerts'][0]['alertRenderer']['text']['runs'][0]['text'])) {
                    $alertMessage = $data['alerts'][0]['alertRenderer']['text']['runs'][0]['text'];
                }

                // Extract Title
                if (isset($data['header']['playlistHeaderRenderer']['title']['runs'][0]['text'])) {
                    $playlistTitle = $data['header']['playlistHeaderRenderer']['title']['runs'][0]['text'];
                } elseif (isset($data['header']['playlistHeaderRenderer']['title']['simpleText'])) {
                    $playlistTitle = $data['header']['playlistHeaderRenderer']['title']['simpleText'];
                } elseif (isset($data['metadata']['playlistMetadataRenderer']['title'])) {
                    $playlistTitle = $data['metadata']['playlistMetadataRenderer']['title'];
                }

                // Extract lockupViewModel items
                $items = $data['contents']['twoColumnBrowseResultsRenderer']['tabs'][0]['tabRenderer']['content']['sectionListRenderer']['contents'][0]['itemSectionRenderer']['contents'] ?? [];

                foreach ($items as $idx => $item) {
                    if (isset($item['lockupViewModel'])) {
                        $lvm = $item['lockupViewModel'];
                        $videoId = $lvm['rendererContext']['commandContext']['onTap']['innertubeCommand']['watchEndpoint']['videoId'] ?? null;
                        $title = $lvm['metadata']['lockupMetadataViewModel']['title']['content'] ?? null;

                        $durationText = null;
                        $durationMinutes = 30;
                        $jsonStr = json_encode($lvm);
                        if (preg_match('/"([0-9]{1,2}:[0-9]{2}(?::[0-9]{2})?)"/', $jsonStr, $timeMatch)) {
                            $durationText = $timeMatch[1];
                            $parts = explode(':', $durationText);
                            if (count($parts) === 2) {
                                $durationMinutes = max(1, (int)$parts[0]);
                            } elseif (count($parts) === 3) {
                                $durationMinutes = ((int)$parts[0] * 60) + (int)$parts[1];
                            }
                        }

                        if ($videoId) {
                            $videos[] = [
                                'video_id' => $videoId,
                                'title' => $title ?: 'Episode ' . ($idx + 1),
                                'duration_text' => $durationText,
                                'duration_minutes' => $durationMinutes,
                                'thumbnail' => "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg",
                                'sort_order' => count($videos) + 1
                            ];
                        }
                    }
                }

                // If lockup didn't match, try recursive search
                if (empty($videos)) {
                    $this->extractVideosRecursive($data, $videos);
                }
            }
        }

        // -------------------------------------------------------------
        // STRATEGY 2: YouTube Atom/RSS Feed (Fallback if HTML blocked)
        // -------------------------------------------------------------
        if (empty($videos)) {
            $rssVideos = $this->fetchFromRssFeed($playlistId, $playlistTitle);
            if (!empty($rssVideos)) {
                $videos = $rssVideos;
            }
        }

        // -------------------------------------------------------------
        // STRATEGY 3: Raw videoId Regex from HTML (Fallback)
        // -------------------------------------------------------------
        if (empty($videos) && !empty($html)) {
            preg_match_all('/"videoId":"([a-zA-Z0-9_-]{11})"/s', $html, $rawVids);
            if (!empty($rawVids[1])) {
                $uniqueIds = array_unique($rawVids[1]);
                $count = 0;
                foreach ($uniqueIds as $vid) {
                    $videos[] = [
                        'video_id' => $vid,
                        'title' => 'Lecture ' . ($count + 1),
                        'duration_text' => '30m',
                        'duration_minutes' => 30,
                        'thumbnail' => "https://img.youtube.com/vi/{$vid}/mqdefault.jpg",
                        'sort_order' => $count + 1
                    ];
                    $count++;
                    if ($count >= 100) break;
                }
            }
        }

        if (empty($videos)) {
            $errMsg = $alertMessage
                ? "YouTube note: {$alertMessage}. Please ensure the playlist is public or unlisted."
                : 'Unable to retrieve videos from this YouTube playlist. Please verify that the link is accessible and the playlist is set to Public or Unlisted.';

            return [
                'success' => false,
                'title' => $playlistTitle,
                'count' => 0,
                'videos' => [],
                'message' => $errMsg
            ];
        }

        return [
            'success' => true,
            'title' => $playlistTitle,
            'count' => count($videos),
            'videos' => $videos,
            'message' => null
        ];
    }

    /**
     * Safely extract and decode ytInitialData JSON from HTML.
     */
    protected function extractJsonFromHtml(string $html): ?array
    {
        // Pattern 1: Standard ytInitialData declaration
        $patterns = [
            '/(?:var\s+|window\[["\']ytInitialData["\']\]\s*=|let\s+|const\s+|ytInitialData\s*=)\s*(\{.+?\});\s*(?:var\s+|window|if\s*\(|<\/script>)/s',
            '/ytInitialData\s*=\s*(\{.+?\});\s*</s',
            '/ytInitialData\s*=\s*(\{.+?\});/s',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                $decoded = json_decode($matches[1], true);
                if ($decoded && is_array($decoded)) {
                    return $decoded;
                }
            }
        }

        // Substring boundary slicing if regex misses
        $pos = strpos($html, 'ytInitialData');
        if ($pos !== false) {
            $start = strpos($html, '{', $pos);
            if ($start !== false) {
                $endScript = strpos($html, '</script>', $start);
                if ($endScript !== false) {
                    $snippet = substr($html, $start, $endScript - $start);
                    $lastSemi = strrpos($snippet, ';');
                    if ($lastSemi !== false) {
                        $possibleJson = trim(substr($snippet, 0, $lastSemi));
                        $decoded = json_decode($possibleJson, true);
                        if ($decoded && is_array($decoded)) {
                            return $decoded;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Fetch videos via YouTube's public Atom/RSS feed.
     */
    protected function fetchFromRssFeed(string $playlistId, string &$playlistTitle): array
    {
        $rssUrl = 'https://www.youtube.com/feeds/videos.xml?playlist_id=' . urlencode($playlistId);

        $ch = curl_init($rssUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            CURLOPT_TIMEOUT => 12,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $xmlStr = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($xmlStr) || !str_contains($xmlStr, '<entry>')) {
            return [];
        }

        $xml = @simplexml_load_string($xmlStr);
        if (!$xml) {
            return [];
        }

        if (!empty($xml->title)) {
            $playlistTitle = (string)$xml->title;
        }

        $videos = [];
        $index = 1;

        foreach ($xml->entry as $entry) {
            $yt = $entry->children('http://www.youtube.com/xml/schemas/2015');
            $videoId = (string)($yt->videoId ?? '');
            $title = (string)($entry->title ?? ('Episode ' . $index));

            if ($videoId) {
                $videos[] = [
                    'video_id' => $videoId,
                    'title' => $title,
                    'duration_text' => '30m',
                    'duration_minutes' => 30,
                    'thumbnail' => "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg",
                    'sort_order' => $index
                ];
                $index++;
            }
        }

        return $videos;
    }

    /**
     * Fallback recursive extractor for custom YouTube layouts.
     */
    protected function extractVideosRecursive(array $node, array &$videos): void
    {
        if (isset($node['playlistVideoRenderer'])) {
            $pvr = $node['playlistVideoRenderer'];
            $videoId = $pvr['videoId'] ?? null;
            $title = $pvr['title']['runs'][0]['text'] ?? $pvr['title']['simpleText'] ?? null;
            $durationText = $pvr['lengthText']['simpleText'] ?? null;
            $lengthSeconds = (int)($pvr['lengthSeconds'] ?? 0);
            $durationMinutes = $lengthSeconds > 0 ? max(1, (int)round($lengthSeconds / 60)) : 30;

            if ($videoId) {
                $videos[] = [
                    'video_id' => $videoId,
                    'title' => $title ?: 'Episode ' . (count($videos) + 1),
                    'duration_text' => $durationText,
                    'duration_minutes' => $durationMinutes,
                    'thumbnail' => "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg",
                    'sort_order' => count($videos) + 1
                ];
            }
            return;
        }

        foreach ($node as $child) {
            if (is_array($child)) {
                $this->extractVideosRecursive($child, $videos);
            }
        }
    }

    /**
     * Fetch single video info via oEmbed.
     */
    public function fetchSingleVideo(string $videoId): array
    {
        $url = 'https://www.youtube.com/oembed?url=' . urlencode('https://www.youtube.com/watch?v=' . $videoId) . '&format=json';
        
        $ctx = stream_context_create([
            'http' => [
                'header' => "User-Agent: Mozilla/5.0\r\n",
                'timeout' => 5
            ]
        ]);

        $res = @file_get_contents($url, false, $ctx);
        if ($res) {
            $json = json_decode($res, true);
            if ($json) {
                return [
                    'title' => $json['title'] ?? 'Lesson',
                    'author' => $json['author_name'] ?? '',
                    'thumbnail' => "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg"
                ];
            }
        }

        return [
            'title' => 'Video Lesson',
            'author' => '',
            'thumbnail' => "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg"
        ];
    }
}
