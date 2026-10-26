@extends('layouts.app')

@section('title', $currentLesson->title . ' — ' . $course->title . ' | Nirmaan')

@section('content')
<div class="py-6 sm:py-8" 
     x-data="{
         activeLessonId: {{ $currentLesson->id }},
         activeVideoId: '{{ $currentLesson->video_id }}',
         activeTitle: '{{ addslashes($currentLesson->title) }}',
         activeEpisodeNum: {{ $currentIndex + 1 }},
         activeDuration: '{{ $currentLesson->duration_text ?? ($currentLesson->duration_minutes . " Mins") }}',
         selectedModule: 'all',
         copiedToast: false,
         playEpisode(ep) {
             this.activeLessonId = ep.id;
             this.activeVideoId = ep.video_id;
             this.activeTitle = ep.title;
             this.activeEpisodeNum = ep.episode_num;
             this.activeDuration = ep.duration_text;
             
             // Update browser URL without reload
             const url = new URL(window.location);
             url.searchParams.set('lesson', ep.id);
             window.history.pushState({}, '', url);

             // Smooth scroll to player on mobile
             if (window.innerWidth < 768) {
                 const playerEl = document.getElementById('theater-player-container');
                 if (playerEl) {
                     playerEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                 }
             }
         },
         copyShareLink() {
             const url = window.location.href;
             navigator.clipboard.writeText(url).then(() => {
                 this.copiedToast = true;
                 setTimeout(() => this.copiedToast = false, 2500);
             });
         }
     }">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Breadcrumbs & Back Navigation (Clean & Understated) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-gray-200/80 gap-3">
            <div class="flex items-center space-x-2 text-xs text-gray-500 font-normal truncate">
                <a href="{{ route('home') }}" class="hover:text-blue-700 transition">Home</a>
                <span class="text-gray-300 font-light">&bull;</span>
                <a href="{{ route('courses.index') }}" class="hover:text-blue-700 transition">Courses</a>
                <span class="text-gray-300 font-light">&bull;</span>
                <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-blue-700 truncate max-w-[200px] sm:max-w-xs transition">{{ $course->title }}</a>
                <span class="text-gray-300 font-light">&bull;</span>
                <span class="text-gray-800 font-medium">Lectures</span>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center space-x-1.5 text-xs font-medium text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Course Overview</span>
                </a>
                @if(Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 hover:bg-gray-200 text-gray-800 transition">
                        <span>Edit Curriculum ✎</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- THEATER VIDEO PLAYER CONTAINER (Sleek Cinema Framing) -->
        <div id="theater-player-container" class="bg-[#0B1120] rounded-2xl p-3.5 sm:p-5 border border-slate-800/90 shadow-sm space-y-4">
            
            <!-- Video Player Iframe -->
            <div class="aspect-video w-full rounded-xl overflow-hidden bg-black relative border border-slate-800/80">
                <template x-if="activeVideoId">
                    <iframe :src="'https://www.youtube-nocookie.com/embed/' + activeVideoId + '?autoplay=1&rel=0&modestbranding=1'" 
                            class="w-full h-full" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>
                </template>
            </div>

            <!-- Video Metadata Bar (Balanced Weight Hierarchy) -->
            <div class="space-y-2.5 pt-1">
                <!-- Badges Row -->
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="px-2.5 py-0.5 rounded-md font-medium text-[11px] bg-amber-500/15 text-amber-300 border border-amber-500/25">
                        Series
                    </span>
                    <span class="px-2.5 py-0.5 rounded-md font-normal text-[11px] bg-slate-800/80 text-slate-300 border border-slate-700/60">
                        HD Lecture
                    </span>
                    <span class="flex items-center space-x-1 text-amber-400 font-medium text-xs">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>4.9</span>
                    </span>
                    <span class="text-slate-600 font-light">&bull;</span>
                    <span class="text-slate-400 font-normal text-xs">{{ date('Y') }} Batch</span>
                    <span class="text-slate-600 font-light">&bull;</span>
                    <span class="text-slate-400 font-normal text-xs">{{ $allLessons->count() }} Lessons</span>
                </div>

                <!-- Course Title (Clean, Authoritative, Not Shouting) -->
                <h1 class="text-lg sm:text-2xl font-bold text-white tracking-tight leading-snug">
                    {{ $course->title }}
                </h1>

                <!-- Sub-banner: Now Playing Bar & Share Action -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pt-2 border-t border-slate-800/80">
                    
                    <!-- Now Playing Capsule -->
                    <div class="inline-flex items-center space-x-2 text-xs text-slate-300 min-w-0">
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-amber-500/15 text-amber-300 border border-amber-500/25 flex-shrink-0">
                            Now Playing
                        </span>
                        <span class="text-slate-500 font-light">&bull;</span>
                        <span class="truncate font-medium text-slate-200">
                            Ep <span x-text="activeEpisodeNum"></span>: <span class="font-normal text-slate-300" x-text="activeTitle"></span>
                        </span>
                    </div>

                    <!-- Share Button (Sleek & Understated) -->
                    <div class="flex items-center space-x-2 flex-shrink-0">
                        <button type="button" 
                                @click="copyShareLink()" 
                                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-300 hover:text-white bg-slate-800/70 hover:bg-slate-800 border border-slate-700/60 transition">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            <span>Share</span>
                        </button>

                        <span x-show="copiedToast" 
                              x-transition:enter="transition ease-out duration-150"
                              x-transition:enter-start="opacity-0 translate-y-1"
                              x-transition:enter-end="opacity-100 translate-y-0"
                              class="text-xs text-emerald-400 font-normal" 
                              style="display:none;">
                            Copied!
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- COURSE DIRECTORY HEADER (Refined Typography) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-4 pb-2 border-b border-gray-200/80">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 tracking-tight">
                    Course Lesson Directory
                </h2>
                <p class="text-xs font-normal text-gray-500 mt-0.5">Structured video lectures for syllabus mastery and exam revision</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="text-xs font-normal text-gray-600 bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200/70">
                    {{ $allLessons->count() }} Lessons Available
                </span>
            </div>
        </div>

        <!-- Module Filter Tabs (if course has multiple modules) -->
        @if($course->modules->count() > 1)
            <div class="flex items-center space-x-2 overflow-x-auto pb-2 custom-scrollbar">
                <button type="button" 
                        @click="selectedModule = 'all'" 
                        :class="selectedModule === 'all' ? 'bg-slate-900 text-white font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 font-normal'"
                        class="px-3 py-1.5 rounded-lg text-xs flex-shrink-0 transition">
                    All Modules ({{ $allLessons->count() }})
                </button>
                @foreach($course->modules as $mod)
                    <button type="button" 
                            @click="selectedModule = {{ $mod->id }}" 
                            :class="selectedModule === {{ $mod->id }} ? 'bg-slate-900 text-white font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 font-normal'"
                            class="px-3 py-1.5 rounded-lg text-xs flex-shrink-0 transition">
                        {{ $mod->title }} ({{ $mod->lessons->count() }})
                    </button>
                @endforeach
            </div>
        @endif

        <!-- EPISODES LIST (Clean Cards with Refined Hierarchy) -->
        <div class="space-y-2.5 pt-1">
            @foreach($allLessons as $index => $lesson)
                @php
                    $epNum = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                    $moduleName = $lesson->module->title ?? 'Module 1';
                @endphp

                <div x-show="selectedModule === 'all' || selectedModule === {{ $lesson->course_module_id }}"
                     @click="playEpisode({
                         id: {{ $lesson->id }},
                         episode_num: {{ $index + 1 }},
                         title: '{{ addslashes($lesson->title) }}',
                         video_id: '{{ $lesson->video_id }}',
                         duration_text: '{{ $lesson->duration_text ?? ($lesson->duration_minutes . " Mins") }}'
                     })"
                     :class="activeLessonId === {{ $lesson->id }} 
                        ? 'border-blue-600 bg-blue-50/40 ring-1 ring-blue-600' 
                        : 'border-gray-200/80 bg-white hover:border-gray-300 hover:bg-gray-50/50'"
                     class="border rounded-xl p-3 sm:p-3.5 flex items-center justify-between gap-3 sm:gap-4 transition-all cursor-pointer shadow-2xs group">
                    
                    <!-- Left: Number & Thumbnail & Text info -->
                    <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-grow">
                        <!-- Number Badge: Sleek Mono Index -->
                        <span :class="activeLessonId === {{ $lesson->id }} ? 'text-blue-700 font-semibold' : 'text-gray-400 font-normal'" 
                              class="w-7 text-center font-mono text-xs sm:text-sm flex-shrink-0">
                            {{ $epNum }}
                        </span>

                        <!-- Video Thumbnail with subtle Duration overlay -->
                        <div class="w-20 sm:w-28 aspect-video rounded-lg overflow-hidden bg-slate-900 relative flex-shrink-0 border border-gray-200/80">
                            <img src="https://img.youtube.com/vi/{{ $lesson->video_id }}/mqdefault.jpg" 
                                 alt="{{ $lesson->title }}" 
                                 loading="lazy" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            @if($lesson->duration_text)
                                <span class="absolute bottom-1 right-1 bg-black/80 text-gray-200 text-[9px] font-normal px-1 py-0.2 rounded">
                                    {{ $lesson->duration_text }}
                                </span>
                            @else
                                <span class="absolute bottom-1 right-1 bg-black/80 text-gray-200 text-[9px] font-normal px-1 py-0.2 rounded">
                                    {{ $lesson->duration_minutes }}m
                                </span>
                            @endif
                        </div>

                        <!-- Episode Title & Info -->
                        <div class="min-w-0 pr-2 space-y-0.5">
                            <span class="text-[11px] font-normal text-gray-400 block truncate">
                                {{ $moduleName }}
                            </span>
                            <h3 :class="activeLessonId === {{ $lesson->id }} ? 'text-blue-950 font-semibold' : 'text-gray-800 font-medium group-hover:text-blue-700'" 
                                class="text-xs sm:text-sm line-clamp-1 transition-colors leading-snug">
                                {{ $lesson->title }}
                            </h3>
                            <div class="flex items-center space-x-2 text-[11px] text-gray-400 font-normal">
                                <span>{{ $lesson->duration_text ?: ($lesson->duration_minutes . ' mins') }}</span>
                                @if($lesson->is_preview)
                                    <span>&bull;</span>
                                    <span class="text-[10px] font-medium text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200/60">
                                        Free Preview
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right: Action Button (Refined & Clean) -->
                    <div class="flex-shrink-0">
                        <template x-if="activeLessonId === {{ $lesson->id }}">
                            <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-blue-700 shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                <span>Playing</span>
                            </div>
                        </template>
                        
                        <template x-if="activeLessonId !== {{ $lesson->id }}">
                            <button type="button" 
                                    class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 hover:text-gray-900 border border-gray-200 transition">
                                <svg class="w-3 h-3 text-gray-500 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                <span>Play</span>
                            </button>
                        </template>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
