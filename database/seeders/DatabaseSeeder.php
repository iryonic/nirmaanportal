<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ExamCategory;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Question;
use App\Models\MockTest;
use App\Models\Order;
use App\Models\Enrollment;
use App\Models\StudyMaterial;
use App\Models\Faq;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin and Student Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@nirmaan.com'],
            [
                'name' => 'Col. R. K. Verma (Director)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+91 98765 43210',
                'target_exam' => 'Defence Administration',
                'is_active' => true,
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'student@nirmaan.com'],
            [
                'name' => 'Aditya Pratap Singh',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'phone' => '+91 91234 56789',
                'target_exam' => 'Indian Army Agniveer',
                'is_active' => true,
            ]
        );

        // 2. Exam Categories
        $categories = [
            [
                'name' => 'Indian Army',
                'slug' => 'indian-army',
                'code' => 'ARMY',
                'icon' => 'army',
                'badge_color' => 'emerald',
                'description' => 'Comprehensive preparation for Agniveer General Duty, Soldier Technical, Clerk, Tradesman, and Nursing Assistant.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Indian Navy',
                'slug' => 'indian-navy',
                'code' => 'NAVY',
                'icon' => 'navy',
                'badge_color' => 'blue',
                'description' => 'Targeted modules for Navy SSR (Senior Secondary Recruit), AA (Artificer Apprentice), MR (Matric Recruit), and Executive branch exams.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Indian Air Force',
                'slug' => 'indian-air-force',
                'code' => 'AIRFORCE',
                'icon' => 'airforce',
                'badge_color' => 'sky',
                'description' => 'Complete curriculum for Agniveervayu Science (Group X) & Other Than Science (Group Y) entrance tests.',
                'sort_order' => 3,
            ],
            [
                'name' => 'BSF',
                'slug' => 'bsf',
                'code' => 'BSF',
                'icon' => 'bsf',
                'badge_color' => 'amber',
                'description' => 'Border Security Force recruitment prep for Constable (GD), Head Constable (RO/RM), and ASI (Steno).',
                'sort_order' => 4,
            ],
            [
                'name' => 'CISF',
                'slug' => 'cisf',
                'code' => 'CISF',
                'icon' => 'cisf',
                'badge_color' => 'orange',
                'description' => 'Central Industrial Security Force coaching for Constable / Fireman, Head Constable Ministerial, and ASI.',
                'sort_order' => 5,
            ],
            [
                'name' => 'SSB',
                'slug' => 'ssb',
                'code' => 'SSB',
                'icon' => 'ssb',
                'badge_color' => 'indigo',
                'description' => 'Sashastra Seema Bal & Services Selection Board interview and Assistant Commandant / Sub-Inspector preparation.',
                'sort_order' => 6,
            ],
            [
                'name' => 'NDA',
                'slug' => 'nda',
                'code' => 'NDA',
                'icon' => 'nda',
                'badge_color' => 'purple',
                'description' => 'National Defence Academy examination preparation for 10+2 cadets including Mathematics & GAT.',
                'sort_order' => 7,
            ],
            [
                'name' => 'CDS',
                'slug' => 'cds',
                'code' => 'CDS',
                'icon' => 'CDS',
                'badge_color' => 'rose',
                'description' => 'Combined Defence Services examination preparation for IMA, INA, AFA, and OTA.',
                'sort_order' => 8,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $catData) {
            $createdCategories[$catData['code']] = ExamCategory::updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        // 3. Courses matching the design
        $coursesData = [
            [
                'category_code' => 'ARMY',
                'title' => 'Indian Army',
                'slug' => 'indian-army-agniveer-comprehensive-course',
                'subtitle' => 'Agniveer / Soldier / Clerk / Technical',
                'description' => 'The ultimate preparation package for Indian Army aspirants. Covers General Science, Mathematics, General Knowledge, and Reasoning with daily live practice, mock tests, and bilingual notes.',
                'price' => 2999.00,
                'original_price' => 4999.00,
                'is_free' => false,
                'is_published' => true,
                'is_featured' => true,
                'instructor_name' => 'Major S. K. Rathore (Retd.) & Expert Faculty',
                'duration_text' => '6 Months Validity',
                'has_live_classes' => true,
                'has_mock_tests' => true,
                'has_study_material' => true,
                'sort_order' => 1,
            ],
            [
                'category_code' => 'NAVY',
                'title' => 'Indian Navy',
                'slug' => 'indian-navy-ssr-aa-mr-batch',
                'subtitle' => 'SSR / AA / MR / Executive',
                'description' => 'Specialized program for Indian Navy recruits. In-depth physics, mathematics, general awareness, and English specifically calibrated for INET & Agniveer Navy computer-based tests.',
                'price' => 2999.00,
                'original_price' => 4999.00,
                'is_free' => false,
                'is_published' => true,
                'is_featured' => true,
                'instructor_name' => 'Commander V. K. Sharma (Retd.)',
                'duration_text' => '6 Months Validity',
                'has_live_classes' => true,
                'has_mock_tests' => true,
                'has_study_material' => true,
                'sort_order' => 2,
            ],
            [
                'category_code' => 'AIRFORCE',
                'title' => 'Indian Air Force',
                'slug' => 'indian-air-force-group-x-y-airmen',
                'subtitle' => 'Group X / Group Y / Airmen',
                'description' => 'Dedicated batch for Agniveervayu Science and Non-Science subjects. Master technical mathematics, physics, and RAGA (Reasoning and General Awareness) with structured drills.',
                'price' => 2999.00,
                'original_price' => 4999.00,
                'is_free' => false,
                'is_published' => true,
                'is_featured' => true,
                'instructor_name' => 'Wing Commander A. Roy (Retd.)',
                'duration_text' => '6 Months Validity',
                'has_live_classes' => true,
                'has_mock_tests' => true,
                'has_study_material' => true,
                'sort_order' => 3,
            ],
            [
                'category_code' => 'BSF',
                'title' => 'BSF',
                'slug' => 'bsf-constable-head-constable-asi',
                'subtitle' => 'Constable / Head Constable / ASI',
                'description' => 'Targeted syllabus coverage for Border Security Force exams. High-yield video lectures, practice quizzes on Hindi/English comprehension, numerical aptitude, and border security duties.',
                'price' => 2999.00,
                'original_price' => 4999.00,
                'is_free' => false,
                'is_published' => true,
                'is_featured' => true,
                'instructor_name' => 'Assistant Commandant P. Nair',
                'duration_text' => '6 Months Validity',
                'has_live_classes' => true,
                'has_mock_tests' => true,
                'has_study_material' => true,
                'sort_order' => 4,
            ],
            [
                'category_code' => 'CISF',
                'title' => 'CISF',
                'slug' => 'cisf-constable-si-asi-batch',
                'subtitle' => 'Constable / SI / ASI',
                'description' => 'Structured syllabus for Central Industrial Security Force aspirants. Learn exam-specific shortcuts, previous year questions, and time management strategies for CBT.',
                'price' => 2999.00,
                'original_price' => 4999.00,
                'is_free' => false,
                'is_published' => true,
                'is_featured' => true,
                'instructor_name' => 'Inspector Rajeshwar Singh (Retd.)',
                'duration_text' => '6 Months Validity',
                'has_live_classes' => true,
                'has_mock_tests' => true,
                'has_study_material' => true,
                'sort_order' => 5,
            ],
            [
                'category_code' => 'SSB',
                'title' => 'SSB',
                'slug' => 'ssb-assistant-commandant-si',
                'subtitle' => 'Assistant Commandant (AC) / SI',
                'description' => 'Comprehensive guidance for CAPF Assistant Commandant and Sub-Inspector recruitment, as well as 5-day SSB interview psychological testing and ground tasks.',
                'price' => 2999.00,
                'original_price' => 4999.00,
                'is_free' => false,
                'is_published' => true,
                'is_featured' => true,
                'instructor_name' => 'Col. D. S. Chauhan (Former Psychologist)',
                'duration_text' => '6 Months Validity',
                'has_live_classes' => true,
                'has_mock_tests' => true,
                'has_study_material' => true,
                'sort_order' => 6,
            ],
        ];

        $createdCourses = [];
        foreach ($coursesData as $courseItem) {
            $cat = $createdCategories[$courseItem['category_code']];
            unset($courseItem['category_code']);
            $courseItem['exam_category_id'] = $cat->id;

            $createdCourses[] = Course::updateOrCreate(
                ['slug' => $courseItem['slug']],
                $courseItem
            );
        }

        // 4. Create Modules and Lessons for Indian Army Course
        $armyCourse = $createdCourses[0];
        $modulesData = [
            [
                'title' => 'Module 1: Orientation & Exam Strategy',
                'description' => 'Understanding the Agniveer syllabus, weightage, mark distribution, and time-saving techniques.',
                'lessons' => [
                    [
                        'title' => 'Agniveer Exam Blueprint & Syllabus Walkthrough',
                        'duration' => 25,
                        'video_id' => 'dQw4w9WgXcQ', // Demo preview
                        'is_preview' => true,
                        'attachment_title' => 'Army_Syllabus_Official_2025.pdf',
                    ],
                    [
                        'title' => 'How to Score 45+ Out of 50 in General Duty Exam',
                        'duration' => 35,
                        'video_id' => '3JZ_D3ELwOQ',
                        'is_preview' => true,
                        'attachment_title' => 'Score_Optimization_Checklist.pdf',
                    ],
                ],
            ],
            [
                'title' => 'Module 2: General Knowledge & Current Affairs',
                'description' => 'Indian History, Geography, Defense Forces, Honors & Awards, Constitution, and International Organizations.',
                'lessons' => [
                    [
                        'title' => 'Indian Defense Structure & Ranks Hierarchy',
                        'duration' => 42,
                        'video_id' => 'L_LUpnjgPso',
                        'is_preview' => false,
                        'attachment_title' => 'Defence_Commands_and_Badges.pdf',
                    ],
                    [
                        'title' => 'Major Battles & Modern Indian Military History',
                        'duration' => 48,
                        'video_id' => 'M7lc1UVf-VE',
                        'is_preview' => false,
                        'attachment_title' => 'Indian_Military_History_Notes.pdf',
                    ],
                    [
                        'title' => 'Indian Constitution & Fundamental Rights for Defence Exams',
                        'duration' => 38,
                        'video_id' => 'kJQP7kiw5Fk',
                        'is_preview' => false,
                        'attachment_title' => 'Polity_One_Liners.pdf',
                    ],
                ],
            ],
            [
                'title' => 'Module 3: General Science (Physics, Chemistry & Biology)',
                'description' => 'Core scientific principles frequently tested in Soldier General Duty and Technical trades.',
                'lessons' => [
                    [
                        'title' => 'Physics: Newton’s Laws of Motion & Gravitation Made Simple',
                        'duration' => 45,
                        'video_id' => 'fJ9rUzIMcZQ',
                        'is_preview' => true, // preview lesson
                        'attachment_title' => 'Physics_Formula_Cheatsheet.pdf',
                    ],
                    [
                        'title' => 'Chemistry: Elements, Compounds & Common Chemical Reactions',
                        'duration' => 40,
                        'video_id' => 'fJ9rUzIMcZQ',
                        'is_preview' => false,
                        'attachment_title' => 'Chemistry_High_Yield_Notes.pdf',
                    ],
                    [
                        'title' => 'Biology: Human Body Systems, Vitamins & Diseases',
                        'duration' => 50,
                        'video_id' => 'fJ9rUzIMcZQ',
                        'is_preview' => false,
                        'attachment_title' => 'Human_Anatomy_Quick_Review.pdf',
                    ],
                ],
            ],
            [
                'title' => 'Module 4: Elementary Mathematics & Arithmetic Shortcuts',
                'description' => 'Percentage, Profit & Loss, Ratio & Proportion, Time & Work, Algebra and Mensuration.',
                'lessons' => [
                    [
                        'title' => 'Fast Calculation Tricks for Defence Aptitude',
                        'duration' => 35,
                        'video_id' => 'kJQP7kiw5Fk',
                        'is_preview' => false,
                        'attachment_title' => 'Speed_Math_Formulae.pdf',
                    ],
                    [
                        'title' => 'Time, Speed and Distance with Train Problems',
                        'duration' => 44,
                        'video_id' => 'kJQP7kiw5Fk',
                        'is_preview' => false,
                        'attachment_title' => 'Time_Speed_Distance_Practice.pdf',
                    ],
                ],
            ],
        ];

        $firstLesson = null;
        $allLessons = [];
        foreach ($modulesData as $mIndex => $mItem) {
            $mod = CourseModule::create([
                'course_id' => $armyCourse->id,
                'title' => $mItem['title'],
                'description' => $mItem['description'],
                'sort_order' => $mIndex + 1,
            ]);

            foreach ($mItem['lessons'] as $lIndex => $lItem) {
                $lesson = Lesson::create([
                    'course_module_id' => $mod->id,
                    'title' => $lItem['title'],
                    'duration_minutes' => $lItem['duration'],
                    'video_provider' => 'youtube',
                    'video_id' => $lItem['video_id'],
                    'is_preview' => $lItem['is_preview'],
                    'attachment_title' => $lItem['attachment_title'],
                    'sort_order' => $lIndex + 1,
                ]);

                if (!$firstLesson) {
                    $firstLesson = $lesson;
                }
                $allLessons[] = $lesson;
            }
        }

        // Also add modules to Air Force course
        $afCourse = $createdCourses[2];
        $afMod = CourseModule::create([
            'course_id' => $afCourse->id,
            'title' => 'Module 1: Agniveervayu Airmen Foundation',
            'description' => 'Core concepts for Science and Other Than Science streams.',
            'sort_order' => 1,
        ]);
        Lesson::create([
            'course_module_id' => $afMod->id,
            'title' => 'Airforce Group X & Y Exam Pattern Breakdown',
            'duration_minutes' => 30,
            'video_provider' => 'youtube',
            'video_id' => '3JZ_D3ELwOQ',
            'is_preview' => true,
            'attachment_title' => 'IAF_Group_X_Y_Syllabus.pdf',
            'sort_order' => 1,
        ]);
        Lesson::create([
            'course_module_id' => $afMod->id,
            'title' => 'RAGA (Reasoning and General Awareness) Strategy',
            'duration_minutes' => 45,
            'video_provider' => 'youtube',
            'video_id' => 'L_LUpnjgPso',
            'is_preview' => false,
            'attachment_title' => 'RAGA_Practice_Set_1.pdf',
            'sort_order' => 2,
        ]);

        // 5. Enroll Student into Army Course & create order
        $order = Order::create([
            'user_id' => $student->id,
            'course_id' => $armyCourse->id,
            'order_number' => 'NIR-ORD-' . strtoupper(Str::random(8)),
            'amount' => 2999.00,
            'currency' => 'INR',
            'status' => 'paid',
            'payment_gateway' => 'razorpay',
            'payment_id' => 'pay_' . Str::random(14),
            'paid_at' => now()->subDays(3),
            'notes' => 'Enrolled via Online Gateway Checkout',
        ]);

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $armyCourse->id,
            'order_id' => $order->id,
            'status' => 'active',
            'enrolled_at' => now()->subDays(3),
            'expires_at' => now()->addMonths(6),
        ]);

        // Mark first 2 lessons as completed to show real progress
        if (count($allLessons) >= 2) {
            LessonCompletion::create([
                'user_id' => $student->id,
                'lesson_id' => $allLessons[0]->id,
                'completed_at' => now()->subDays(2),
            ]);
            LessonCompletion::create([
                'user_id' => $student->id,
                'lesson_id' => $allLessons[1]->id,
                'completed_at' => now()->subDay(),
            ]);
        }

        // 6. Practice Questions Bank
        $questionsData = [
            [
                'subject' => 'General Knowledge',
                'topic' => 'Indian Defense',
                'difficulty' => 'easy',
                'question_text' => 'Who is the Supreme Commander of the Indian Armed Forces?',
                'option_a' => 'Prime Minister of India',
                'option_b' => 'President of India',
                'option_c' => 'Chief of Defence Staff (CDS)',
                'option_d' => 'Minister of Defence',
                'correct_option' => 'B',
                'explanation' => 'Under Article 53 of the Constitution of India, the President of India is the supreme commander of the defence forces of the Union.',
            ],
            [
                'subject' => 'General Knowledge',
                'topic' => 'Defense Establishments',
                'difficulty' => 'easy',
                'question_text' => 'Where is the National Defence Academy (NDA) located?',
                'option_a' => 'Dehradun, Uttarakhand',
                'option_b' => 'Khadakwasla, Pune, Maharashtra',
                'option_c' => 'Dundigal, Hyderabad',
                'option_d' => 'Ezhimala, Kerala',
                'correct_option' => 'B',
                'explanation' => 'The National Defence Academy (NDA) is located at Khadakwasla near Pune, Maharashtra. It is the joint defence training academy for Army, Navy, and Air Force.',
            ],
            [
                'subject' => 'General Science',
                'topic' => 'Physics',
                'difficulty' => 'medium',
                'question_text' => 'What is the SI unit of electric current?',
                'option_a' => 'Volt',
                'option_b' => 'Ohm',
                'option_c' => 'Ampere',
                'option_d' => 'Watt',
                'correct_option' => 'C',
                'explanation' => 'The SI unit of electric current is the Ampere (symbol: A). Volt is the unit of potential difference, Ohm is for electrical resistance, and Watt is for power.',
            ],
            [
                'subject' => 'General Science',
                'topic' => 'Chemistry',
                'difficulty' => 'easy',
                'question_text' => 'Which gas is commonly known as "Laughing Gas"?',
                'option_a' => 'Nitrous Oxide (N2O)',
                'option_b' => 'Nitric Oxide (NO)',
                'option_c' => 'Nitrogen Dioxide (NO2)',
                'option_d' => 'Sulfur Dioxide (SO2)',
                'correct_option' => 'A',
                'explanation' => 'Nitrous oxide (N2O), commonly known as laughing gas, is a chemical compound of nitrogen and oxygen with anesthetic and pain-relieving effects.',
            ],
            [
                'subject' => 'General Science',
                'topic' => 'Biology',
                'difficulty' => 'medium',
                'question_text' => 'Which organ in the human body produces bile juice?',
                'option_a' => 'Pancreas',
                'option_b' => 'Gallbladder',
                'option_c' => 'Liver',
                'option_d' => 'Stomach',
                'correct_option' => 'C',
                'explanation' => 'Bile is produced by the liver and stored/concentrated in the gallbladder until it is needed to digest fats in the small intestine.',
            ],
            [
                'subject' => 'Mathematics',
                'topic' => 'Arithmetic',
                'difficulty' => 'medium',
                'question_text' => 'If a soldier covers a distance of 1600 meters in 5 minutes and 20 seconds, what is his speed in km/h?',
                'option_a' => '16 km/h',
                'option_b' => '18 km/h',
                'option_c' => '20 km/h',
                'option_d' => '24 km/h',
                'correct_option' => 'B',
                'explanation' => 'Time = 5 min 20 sec = 320 seconds. Speed = 1600 m / 320 s = 5 m/s. To convert to km/h, multiply by 18/5: 5 * (18/5) = 18 km/h.',
            ],
            [
                'subject' => 'Mathematics',
                'topic' => 'Percentage & Profit',
                'difficulty' => 'easy',
                'question_text' => 'A trader buys military boots for ₹ 800 and sells them for ₹ 1,000. What is his percentage profit?',
                'option_a' => '20%',
                'option_b' => '25%',
                'option_c' => '15%',
                'option_d' => '30%',
                'correct_option' => 'B',
                'explanation' => 'Profit = ₹ 1,000 - ₹ 800 = ₹ 200. Profit Percentage = (200 / 800) * 100 = 25%.',
            ],
            [
                'subject' => 'Reasoning',
                'topic' => 'Analogy & Series',
                'difficulty' => 'easy',
                'question_text' => 'Complete the series: 3, 7, 15, 31, 63, ?',
                'option_a' => '127',
                'option_b' => '125',
                'option_c' => '129',
                'option_d' => '121',
                'correct_option' => 'A',
                'explanation' => 'Each number is obtained by multiplying the previous number by 2 and adding 1: (3*2)+1=7, (7*2)+1=15, (15*2)+1=31, (31*2)+1=63, (63*2)+1=127.',
            ],
            [
                'subject' => 'General Knowledge',
                'topic' => 'Geography & Borders',
                'difficulty' => 'medium',
                'question_text' => 'The Radcliffe Line is the international boundary between India and which country?',
                'option_a' => 'China',
                'option_b' => 'Pakistan',
                'option_c' => 'Bangladesh',
                'option_d' => 'Afghanistan',
                'correct_option' => 'B',
                'explanation' => 'The Radcliffe Line was published on 17 August 1947 as a boundary demarcation between the Indian and Pakistani portions of the Punjab and Bengal provinces.',
            ],
            [
                'subject' => 'English',
                'topic' => 'Synonyms & Vocabulary',
                'difficulty' => 'medium',
                'question_text' => 'Choose the word most nearly opposite in meaning (Antonym) to "VALIANT":',
                'option_a' => 'Heroic',
                'option_b' => 'Courageous',
                'option_c' => 'Cowardly',
                'option_d' => 'Stalwart',
                'correct_option' => 'C',
                'explanation' => 'Valiant means possessing or showing courage or determination. Cowardly is the direct antonym.',
            ],
        ];

        $savedQuestions = [];
        $armyCategory = $createdCategories['ARMY'];
        foreach ($questionsData as $qData) {
            $savedQuestions[] = Question::create([
                'exam_category_id' => $armyCategory->id,
                'subject' => $qData['subject'],
                'topic' => $qData['topic'],
                'difficulty' => $qData['difficulty'],
                'question_text' => $qData['question_text'],
                'option_a' => $qData['option_a'],
                'option_b' => $qData['option_b'],
                'option_c' => $qData['option_c'],
                'option_d' => $qData['option_d'],
                'correct_option' => $qData['correct_option'],
                'explanation' => $qData['explanation'],
                'marks' => 2.00,
                'negative_marks' => 0.50,
                'is_active' => true,
            ]);
        }

        // 7. Mock Tests
        $mockTest1 = MockTest::create([
            'exam_category_id' => $armyCategory->id,
            'title' => 'Indian Army Agniveer GD Full Pattern Mock Test 01',
            'slug' => 'army-agniveer-gd-mock-test-01',
            'description' => 'Real-time simulated exam strictly adhering to the latest Indian Army Agniveer computer-based test pattern with negative marking.',
            'duration_minutes' => 60,
            'total_marks' => 100.00,
            'pass_marks' => 35.00,
            'negative_marking_ratio' => 0.25,
            'is_free' => true,
            'is_published' => true,
            'instructions' => "1. The test consists of multiple-choice questions across GK, Science, Maths, and Reasoning.\n2. Each correct answer carries 2 marks.\n3. Each wrong answer attracts a penalty of 0.5 marks (negative marking).\n4. You can review and change your answers anytime before final submission.",
        ]);

        foreach ($savedQuestions as $index => $q) {
            $mockTest1->questions()->attach($q->id, ['sort_order' => $index + 1]);
        }

        $mockTest2 = MockTest::create([
            'exam_category_id' => $createdCategories['AIRFORCE']->id,
            'title' => 'Indian Air Force Agniveervayu Science Mock Test 01',
            'slug' => 'iaf-agniveervayu-science-mock-01',
            'description' => 'Specialized speed test covering Physics, Mathematics, and English for Group X Airmen aspirants.',
            'duration_minutes' => 60,
            'total_marks' => 70.00,
            'pass_marks' => 30.00,
            'negative_marking_ratio' => 0.25,
            'is_free' => true,
            'is_published' => true,
            'instructions' => "1. Time limit: 60 minutes.\n2. Negative marking: 0.25 marks for every incorrect response.\n3. Keep scrap paper ready for numerical calculations.",
        ]);

        foreach (array_slice($savedQuestions, 0, 5) as $index => $q) {
            $mockTest2->questions()->attach($q->id, ['sort_order' => $index + 1]);
        }

        // 8. Study Materials
        $studyMaterials = [
            [
                'exam_category_id' => $armyCategory->id,
                'title' => 'Indian Army General Duty Complete Syllabus & Physical Standards Guide',
                'slug' => 'army-gd-complete-syllabus-guide',
                'type' => 'syllabus',
                'description' => 'Comprehensive official guideline outlining height, chest, 1.6km running time brackets, and written test topics.',
                'file_size' => '2.4 MB',
                'is_free' => true,
                'downloads_count' => 1420,
            ],
            [
                'exam_category_id' => $armyCategory->id,
                'title' => 'General Science 500 High-Yield One Liners for Defence Exams',
                'slug' => 'general-science-500-high-yield-one-liners',
                'type' => 'pdf',
                'description' => 'Bilingual revision sheet curated by ex-defence faculty covering recurrent physics and chemistry questions.',
                'file_size' => '4.8 MB',
                'is_free' => true,
                'downloads_count' => 3890,
            ],
            [
                'exam_category_id' => $createdCategories['NAVY']->id,
                'title' => 'Indian Navy SSR/AA Previous Year Solved Question Paper',
                'slug' => 'navy-ssr-aa-previous-year-paper',
                'type' => 'previous_paper',
                'description' => 'Complete solved paper with detailed step-by-step mathematical explanations and answer keys.',
                'file_size' => '3.1 MB',
                'is_free' => true,
                'downloads_count' => 2150,
            ],
        ];

        foreach ($studyMaterials as $sm) {
            StudyMaterial::updateOrCreate(['slug' => $sm['slug']], $sm);
        }

        // 9. FAQs matching design image
        $faqs = [
            [
                'question' => 'What is the validity of the courses?',
                'answer' => 'Each course comes with 6 months of complete access from the date of enrollment. During this period, you have unlimited access to recorded lectures, revision notes, practice sets, and mock exams.',
                'category' => 'courses',
                'sort_order' => 1,
            ],
            [
                'question' => 'Are the mock tests based on the latest exam pattern?',
                'answer' => 'Yes, absolutely. All our mock tests are designed according to the exact current syllabus, marking scheme, negative marking criteria, and time constraints prescribed by the official defence recruitment boards.',
                'category' => 'tests',
                'sort_order' => 2,
            ],
            [
                'question' => 'Can I access the classes on mobile?',
                'answer' => 'Yes! The Nirmaan platform is fully mobile-responsive and designed specifically for seamless smartphone learning. You can watch classes, take timed mock tests, and read notes smoothly on any mobile browser.',
                'category' => 'general',
                'sort_order' => 3,
            ],
            [
                'question' => 'What payment methods are available?',
                'answer' => 'We accept all major Indian payment methods including UPI (Google Pay, PhonePe, Paytm), Debit & Credit Cards (RuPay, Visa, Mastercard), Net Banking across all major banks, and mobile wallets.',
                'category' => 'payment',
                'sort_order' => 4,
            ],
            [
                'question' => 'Is there a refund policy?',
                'answer' => 'We offer a 48-hour student satisfaction window. If you experience technical issues or find that a course was purchased by mistake, reach out to our dedicated support team for assistance.',
                'category' => 'payment',
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $f) {
            Faq::updateOrCreate(['question' => $f['question']], $f);
        }

        // 10. Site Settings
        $settings = [
            'hero_title' => 'Build a Strong Future with Nirmaan',
            'hero_subtitle' => 'Nirmaan provides curated study material, mock tests and online classes for aspirants preparing for security forces entrance examinations.',
            'contact_phone' => '+91 98765 43210',
            'contact_email' => 'support@nirmaan.com',
            'contact_address' => 'Nirmaan Defence Academy, Sector 12, Defence Colony, New Delhi - 110024',
            'youtube_url' => 'https://youtube.com/@nirmaandefence',
            'telegram_url' => 'https://t.me/nirmaandefence',
            'instagram_url' => 'https://instagram.com/nirmaandefence',
            'facebook_url' => 'https://facebook.com/nirmaandefence',
            'twitter_url' => 'https://x.com/nirmaandefence',
        ];

        foreach ($settings as $k => $v) {
            SiteSetting::set($k, $v);
        }
    }
}
