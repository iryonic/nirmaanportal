<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : null;

        $notices = [
            [
                'title' => 'Indian Army Agniveer Rally 2026-27: Online Registration & CEE Exam Schedule Released',
                'category' => 'Indian Army',
                'description' => 'Official notification regarding Agniveer General Duty, Technical, Clerk, and Tradesmen recruitment. Online applications are now live on Join Indian Army portal.',
                'external_url' => 'https://joinindianarmy.nic.in',
                'attachment_path' => null,
                'attachment_name' => null,
                'attachment_size' => null,
                'is_published' => true,
                'published_at' => Carbon::now()->subHours(6), // ~6 hours ago (NEW badge ACTIVE)
                'opens_in_new_tab' => true,
                'is_pinned' => true,
                'sort_order' => 1,
                'views_count' => 142,
                'created_by' => $adminId,
            ],
            [
                'title' => 'NDA & NA Examination (I) 2027 Official Notification & Syllabus Blueprint Published',
                'category' => 'NDA / CDS',
                'description' => 'Union Public Service Commission (UPSC) has issued the detailed guidelines and physical eligibility criteria for admission into Army, Navy, and Air Force wings of NDA.',
                'external_url' => 'https://upsc.gov.in',
                'attachment_path' => null,
                'attachment_name' => 'NDA_Exam_Guidelines_2026.pdf',
                'attachment_size' => '2.4 MB',
                'is_published' => true,
                'published_at' => Carbon::now()->subHours(26), // ~26 hours ago (NEW badge ACTIVE)
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 2,
                'views_count' => 98,
                'created_by' => $adminId,
            ],
            [
                'title' => 'IAF Agniveervayu (01/2027) STAR Written CBT Phase-1 Admit Cards Available for Download',
                'category' => 'Indian Air Force',
                'description' => 'Indian Air Force CASB portal has enabled hall ticket downloads for Science and Other than Science stream candidates. Verify examination centre and reporting timings.',
                'external_url' => 'https://agnipathvayu.cdac.in',
                'attachment_path' => null,
                'attachment_name' => null,
                'attachment_size' => null,
                'is_published' => true,
                'published_at' => Carbon::now()->subHours(50), // ~50 hours ago (NEW badge ACTIVE)
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 3,
                'views_count' => 265,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Indian Navy SSR & MR Computer-Based Test Answer Keys & Objection Window Instructions',
                'category' => 'Indian Navy',
                'description' => 'Candidates who appeared in the recently conducted Indian Navy Sailor examination can now view provisional answer keys and challenge any discrepancies before the cut-off date.',
                'external_url' => 'https://www.joinindiannavy.gov.in',
                'attachment_path' => null,
                'attachment_name' => 'Navy_Answer_Key_Notice.pdf',
                'attachment_size' => '1.1 MB',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5), // ~120 hours ago (NEW badge EXPIRED >72h)
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 4,
                'views_count' => 410,
                'created_by' => $adminId,
            ],
            [
                'title' => 'BSF / CISF Constable (GD) Paramilitary Physical Standard Test (PST/PET) Guidelines',
                'category' => 'Paramilitary',
                'description' => 'Detailed physical measurement instructions, race timings, certificate formats, and documentation requirements for shortlisted paramilitary GD candidates.',
                'external_url' => null,
                'attachment_path' => null,
                'attachment_name' => 'Paramilitary_PST_Instructions.pdf',
                'attachment_size' => '850 KB',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(9), // ~216 hours ago (NEW badge EXPIRED)
                'opens_in_new_tab' => false,
                'is_pinned' => false,
                'sort_order' => 5,
                'views_count' => 530,
                'created_by' => $adminId,
            ],
            [
                'title' => '[Draft Notice] Upcoming CDS-II Final Cut-Off & SSB Interview Call Letter Schedule',
                'category' => 'NDA / CDS',
                'description' => 'Draft notice regarding Service Selection Board (SSB) reporting dates for recommended CDS aspirants.',
                'external_url' => null,
                'attachment_path' => null,
                'attachment_name' => null,
                'attachment_size' => null,
                'is_published' => false, // DRAFT
                'published_at' => null,
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 6,
                'views_count' => 0,
                'created_by' => $adminId,
            ],
        ];

        foreach ($notices as $notice) {
            Notification::updateOrCreate(
                ['title' => $notice['title']],
                $notice
            );
        }
    }
}
