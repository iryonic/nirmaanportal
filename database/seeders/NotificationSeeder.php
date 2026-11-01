<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'slug' => 'indian-army-agniveer-rally-2026-27-online-registration',
                'category' => 'Indian Army',
                'description' => 'Official notification regarding Agniveer General Duty (GD), Technical, Clerk/SKT, and Tradesmen recruitment. Online applications are now live on Join Indian Army portal.',
                'content' => "The Indian Army has announced the online registration schedule for the Agniveer Recruitment Rally 2026-27 under the Agnipath scheme. Eligible unmarried male and female candidates can register online.\n\n### Key Highlights:\n- **Registration Window**: Open now via official Join Indian Army portal\n- **Computer-Based Common Entrance Exam (CEE)**: Scheduled nationwide\n- **Eligible Posts**: Agniveer General Duty, Agniveer Technical, Agniveer Clerk / Store Keeper Technical, and Agniveer Tradesmen (10th & 8th pass)\n- **Selection Process**: Phase-I (Online CEE), Phase-II (Recruitment Rally - Physical Fitness & Measurement), Phase-III (Medical Examination).\n\nCandidates are advised to thoroughly verify physical measurement requirements and educational qualification criteria before applying.",
                'external_url' => 'https://joinindianarmy.nic.in',
                'attachment_path' => null,
                'attachment_name' => 'Army_Agniveer_2026_Notification.pdf',
                'attachment_size' => '3.8 MB',
                'is_published' => true,
                'published_at' => Carbon::now()->subHours(4), // Active NEW badge (<72h)
                'opens_in_new_tab' => true,
                'is_pinned' => true,
                'sort_order' => 1,
                'views_count' => 342,
                'created_by' => $adminId,
            ],
            [
                'title' => 'NDA & NA Examination (I) 2027 Official Notification & Syllabus Blueprint Published',
                'slug' => 'nda-na-examination-1-2027-official-notification',
                'category' => 'NDA / CDS',
                'description' => 'Union Public Service Commission (UPSC) has issued the detailed notification and scheme of examination for admission to Army, Navy, and Air Force wings of NDA.',
                'content' => "The Union Public Service Commission (UPSC) has released the official notification for the National Defence Academy and Naval Academy Examination (I) 2027.\n\n### Exam Architecture:\n- **Paper I**: Mathematics (300 Marks - 2.5 Hours)\n- **Paper II**: General Ability Test (GAT) (600 Marks - 2.5 Hours)\n- **SSB Interview**: 900 Marks\n- **Negative Marking**: Applicable at 1/3rd penalty for each wrong MCQ.\n\nCandidates currently in Class 12 or who have passed Class 12 are eligible to apply. Check the official UPSC portal for online application instructions.",
                'external_url' => 'https://upsc.gov.in',
                'attachment_path' => null,
                'attachment_name' => 'UPSC_NDA_Exam_Guidelines_2027.pdf',
                'attachment_size' => '2.4 MB',
                'is_published' => true,
                'published_at' => Carbon::now()->subHours(18), // Active NEW badge (<72h)
                'opens_in_new_tab' => true,
                'is_pinned' => true,
                'sort_order' => 2,
                'views_count' => 285,
                'created_by' => $adminId,
            ],
            [
                'title' => 'IAF Agniveervayu (01/2027) STAR Phase-1 Online CBT Admit Cards Available for Download',
                'slug' => 'iaf-agniveervayu-01-2027-star-phase-1-admit-card',
                'category' => 'Admit Card',
                'description' => 'Indian Air Force CASB portal has enabled hall ticket downloads for Science and Other than Science stream candidates. Verify examination centre and reporting timings.',
                'content' => "Indian Air Force has uploaded the Admit Cards for Agniveervayu Intake 01/2027 on the candidate login portal at agnipathvayu.cdac.in.\n\n### Candidate Instructions:\n- Print colour copy of the Admit Card on clean A4 paper.\n- Carry original Aadhaar Card and two passport size colour photographs.\n- Adhere strictly to the reporting time printed on the hall ticket.\n- Electronic devices, calculators, smart watches, and bags are prohibited inside the testing hall.",
                'external_url' => 'https://agnipathvayu.cdac.in',
                'attachment_path' => null,
                'attachment_name' => null,
                'attachment_size' => null,
                'is_published' => true,
                'published_at' => Carbon::now()->subHours(36), // Active NEW badge (<72h)
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 3,
                'views_count' => 512,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Indian Navy SSR & MR Computer-Based Test (CBT) Provisional Answer Key & Objection Window',
                'slug' => 'indian-navy-ssr-mr-cbt-provisional-answer-key',
                'category' => 'Indian Navy',
                'description' => 'Candidates who appeared in the recently conducted Indian Navy Sailor examination can now view provisional answer keys and challenge discrepancies before the cut-off date.',
                'content' => "The Indian Navy has published provisional answer keys and question papers for Agniveer SSR and MR computer-based online tests.\n\n### Objection Submission Procedure:\n- Log in to your candidate portal with registered credentials.\n- Navigate to 'Challenge Answer Key' section.\n- Submit documentary proof from standard NCERT / official textbooks for questioned questions.\n- Non-refundable objection fee of ₹100 per question applies.",
                'external_url' => 'https://www.joinindiannavy.gov.in',
                'attachment_path' => null,
                'attachment_name' => 'Navy_Answer_Key_Notice.pdf',
                'attachment_size' => '1.1 MB',
                'is_published' => true,
                'published_at' => Carbon::now()->subHours(64), // Active NEW badge (<72h)
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 4,
                'views_count' => 420,
                'created_by' => $adminId,
            ],
            [
                'title' => 'SSC GD Constable Paramilitary Recruitment: Physical Standard & Endurance Test (PST/PET) Guidelines',
                'slug' => 'ssc-gd-constable-paramilitary-pst-pet-guidelines',
                'category' => 'Paramilitary',
                'description' => 'Detailed physical measurement instructions, race timings, certificate formats, and documentation requirements for shortlisted paramilitary GD candidates.',
                'content' => "Staff Selection Commission along with CAPF nodal authorities have published the Physical Efficiency Test (PET) and Physical Standard Test (PST) schedule for Constable (GD) in BSF, CISF, CRPF, SSB, ITBP, AR, and SSF.\n\n### Physical Efficiency Test Benchmarks:\n- **Male Candidates**: 5 km run in 24 minutes.\n- **Female Candidates**: 1.6 km run in 8½ minutes.\n- **Height Requirement**: Male (170 cms), Female (157 cms) with standard relaxation for reserved and hill region categories.\n- **Chest (Male only)**: 80 cms unexpanded with 5 cms minimum expansion.",
                'external_url' => 'https://ssc.gov.in',
                'attachment_path' => null,
                'attachment_name' => 'Paramilitary_PST_Instructions.pdf',
                'attachment_size' => '850 KB',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5), // Badge expired (>72h)
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 5,
                'views_count' => 670,
                'created_by' => $adminId,
            ],
            [
                'title' => 'AFCAT (02/2026) Written Examination Results Declared & AFSB Interview Slot Booking Live',
                'slug' => 'afcat-02-2026-written-examination-results-afsb-booking',
                'category' => 'Results',
                'description' => 'Air Force Common Admission Test results have been uploaded on the candidate dashboard. Shortlisted aspirants must select their Air Force Selection Board (AFSB) venue.',
                'content' => "Indian Air Force has declared the written result for AFCAT 02/2026. Aspirants meeting the cut-off benchmark can now log in to choose their preferred AFSB testing venue (Dehradun, Mysuru, Gandhinagar, Varanasi).\n\nSlot booking operates on a first-come, first-served basis. If a candidate fails to choose an interview slot before the closing window, a venue and date will be automatically assigned.",
                'external_url' => 'https://afcat.cdac.in',
                'attachment_path' => null,
                'attachment_name' => null,
                'attachment_size' => null,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(8),
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 6,
                'views_count' => 890,
                'created_by' => $adminId,
            ],
            [
                'title' => 'CDS (I) 2027 Examination Timetable, Shift Timings & OMR Instructions Released by UPSC',
                'slug' => 'cds-1-2027-examination-timetable-shift-timings',
                'category' => 'Exam Schedule',
                'description' => 'Combined Defence Services Examination shift timings for English, General Knowledge, and Elementary Mathematics published.',
                'content' => "The Union Public Service Commission (UPSC) has announced the detailed timetable for the CDS (I) 2027 Examination.\n\n### Shift Timings:\n- **Session 1 (09:00 AM - 11:00 AM)**: English (100 Marks)\n- **Session 2 (12:00 PM - 02:00 PM)**: General Knowledge (100 Marks)\n- **Session 3 (03:00 PM - 05:00 PM)**: Elementary Mathematics (100 Marks - For IMA, INA, AFA only)\n\nCandidates must only use black ballpoint pens for darkening circles on the OMR sheet.",
                'external_url' => 'https://upsc.gov.in',
                'attachment_path' => null,
                'attachment_name' => 'CDS_Exam_Timetable_2027.pdf',
                'attachment_size' => '620 KB',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(12),
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 7,
                'views_count' => 380,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Nirmaan Super 30 Defence Batch 2026-27: Scholarship Entrance Test & Online Registration',
                'slug' => 'nirmaan-super-30-defence-batch-scholarship-test',
                'category' => 'Admissions',
                'description' => 'Apply for Nirmaan Academy 100% scholarship entrance exam for Agniveer, NDA, and AFCAT classroom & live online test batches.',
                'content' => "Nirmaan Defence Academy invites applications for the Super 30 Annual Defence Talent Scholarship Examination.\n\n### Scholarship Perks:\n- Top 30 meritorious cadets receive 100% fee waiver on all study courses and CBT mock test series.\n- Special SSB interview orientation, group obstacle coaching, and psychological screening guidance.\n- Daily interactive question bank and live doubt clearance sessions.",
                'external_url' => null,
                'attachment_path' => null,
                'attachment_name' => 'Scholarship_Syllabus_Form.pdf',
                'attachment_size' => '1.4 MB',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(15),
                'opens_in_new_tab' => false,
                'is_pinned' => false,
                'sort_order' => 8,
                'views_count' => 1120,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Advisory: Beware of Fraudulent Recruitment Portals and Unauthorised Agents',
                'slug' => 'advisory-beware-fraudulent-recruitment-agents',
                'category' => 'General',
                'description' => 'Important security bulletin issued by the defence services warning aspirants against touts, impersonators, and unauthorized fee requests.',
                'content' => "Aspirants are strongly warned that recruitment into the Armed Forces (Army, Navy, Air Force) is purely merit-based, free, fair, and transparent.\n\nNo individual, agent, or commercial agency can influence the selection process. Candidates should register only on official government websites (.nic.in / .gov.in domains) and report any extortion attempts or unauthorized fee demands to nearest police or anti-corruption authorities.",
                'external_url' => null,
                'attachment_path' => null,
                'attachment_name' => null,
                'attachment_size' => null,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(20),
                'opens_in_new_tab' => false,
                'is_pinned' => false,
                'sort_order' => 9,
                'views_count' => 1450,
                'created_by' => $adminId,
            ],
            [
                'title' => '[Draft Notice] Upcoming SSB Interview Call Letter Schedule & Medical Check-Up Protocol',
                'slug' => 'draft-upcoming-ssb-interview-call-letter-schedule',
                'category' => 'NDA / CDS',
                'description' => 'Draft administrative notice outlining reporting instructions, dress code, and medical clearance certificates required for SSB candidates.',
                'content' => "This is a draft notice for upcoming SSB batches containing internal guidelines, reporting dates, medical guidelines, and documentation requirements.",
                'external_url' => null,
                'attachment_path' => null,
                'attachment_name' => null,
                'attachment_size' => null,
                'is_published' => false, // Draft for admin testing
                'published_at' => null,
                'opens_in_new_tab' => true,
                'is_pinned' => false,
                'sort_order' => 10,
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
