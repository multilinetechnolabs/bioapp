<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseFreemiusTransaction;
use App\Models\CourseLessonProgress;
use App\Models\CoursePurchase;
use App\Models\Payment;
use App\Support\CourseContent;
use Illuminate\Http\Request;
use Auth;

class CourseController extends Controller
{
    // Entry point for the homepage "Purchase Now" link. Logged-in users are sent
    // straight to the right course page. Guests get remembered (via session) so
    // login/register can send them back here afterward, then off to login.
    public function start()
    {
        $course = Course::where('is_active', true)->first();

        if (Auth::check()) {
            if ($course && CoursePurchase::userHasAccess(Auth::id(), $course->id)) {
                return redirect()->route('course.index');
            }

            return redirect()->route('course.checkout');
        }

        session(['course_login_intent' => true]);

        return redirect()->route('login');
    }

    public function checkout()
    {
        $course = Course::where('is_active', true)->first();
        $hasAccess = $course && CoursePurchase::userHasAccess(Auth::id(), $course->id);
        $hasExpired = $course && !$hasAccess && CoursePurchase::where('user_id', Auth::id())->where('course_id', $course->id)->exists();

        return view('app.pages.course.checkout', [
            'alreadyPaid' => $hasAccess,
            'hasExpired' => $hasExpired,
            'status' => request('status'),
            'course' => $course,
        ]);
    }

    public function freemiusInit(Request $request)
    {
        $course = Course::where('is_active', true)->first();
        abort_unless($course, 404);

        if (CoursePurchase::userHasAccess(Auth::id(), $course->id)) {
            return response()->json([
                'success' => false,
                'transaction_id' => '',
                'message' => 'You already have access to this course.',
            ]);
        }

        $planId = config('freemius.course_plan_id');
        $productId = config('freemius.product_id');
        $publicKey = config('freemius.public_key');
        $secretKey = config('freemius.secret_key');
        $sandbox = config('freemius.sandbox');

        if (empty($planId) || empty($productId) || empty($publicKey) || empty($secretKey)) {
            return response()->json([
                'success' => false,
                'transaction_id' => '',
                'message' => 'Payment gateway is not configured properly.',
            ]);
        }

        $transaction = CourseFreemiusTransaction::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'amount' => $course->price,
            'currency' => 'USD',
            'status' => 'pending',
            'customer_email' => Auth::user()->email,
        ]);

        $timestamp = time();
        $sandboxToken = md5($timestamp . $productId . $secretKey . $publicKey . 'checkout');

        $return = [
            'success' => true,
            'transaction_id' => $transaction->id,
            'product_id' => $productId,
            'plan_id' => $planId,
            'public_key' => $publicKey,
            'purchase_name' => $course->title,
            'email' => Auth::user()->email,
            'licenses' => 1,
            'image' => url('/favicon.ico'),
            'sandbox' => $sandbox,
        ];

        if ($sandbox) {
            $return['sandbox_token'] = $sandboxToken;
            $return['sandbox_ctx'] = $timestamp;
        }

        return response()->json($return);
    }

    public function freemiusSuccess(Request $request)
    {
        $payload = $request->all();

        \Log::info('Course Freemius Success Callback', $payload);

        $response = $payload['response'] ?? [];
        $purchase = $response['purchase'] ?? [];

        $transactionId = $purchase['id'] ?? null;
        $subscriptionId = $purchase['external_id'] ?? null;
        $licenseId = $purchase['license_id'] ?? null;

        $transaction = CourseFreemiusTransaction::find($payload['transaction_id'] ?? null);

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found']);
        }

        if ($transaction->status === 'paid') {
            return response()->json(['success' => true, 'message' => 'Already processed']);
        }

        $transaction->update([
            'status' => 'paid',
            'freemius_transaction_id' => $transactionId,
            'freemius_subscription_id' => $subscriptionId,
            'freemius_license_key' => $licenseId,
            'payload' => $payload,
            'paid_at' => now(),
        ]);

        $coursePurchase = CoursePurchase::create([
            'user_id' => $transaction->user_id,
            'course_id' => $transaction->course_id,
            'freemius_subscription_id' => $subscriptionId,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addYear(),
        ]);

        $payment = new Payment([
            'user_id' => $transaction->user_id,
            'amount' => $purchase['initial_amount'] ?? $transaction->amount,
            'date_paid' => now(),
        ]);
        $payment->resource_id = $coursePurchase->id;
        $payment->resource_type = CoursePurchase::class;
        $payment->description = 'Course enrollment purchase';
        $payment->save();

        return response()->json(['success' => true]);
    }

    public function freemiusFailed(Request $request)
    {
        $payload = $request->all();

        \Log::info('Course Freemius Failed Callback', $payload);

        $transaction = CourseFreemiusTransaction::find($payload['transaction_id'] ?? null);

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found']);
        }

        if ($transaction->status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Transaction already paid']);
        }

        $transaction->update(['status' => 'failed', 'payload' => $payload]);

        return response()->json(['success' => true, 'message' => 'Payment marked as failed']);
    }

    public function index()
    {
        $completedIds = $this->completedLessonIds();
        $totalLessons = CourseContent::totalLessons();
        $totalCompleted = count($completedIds);
        $freeNavigation = $totalLessons > 0 && $totalCompleted >= $totalLessons;

        $moduleStates = [];

        foreach (CourseContent::modules() as $number => $module) {
            $lessonCount = count($module['slides']);
            $completedInModule = $this->completedCountInModule($number, $completedIds);

            $moduleStates[] = [
                'number' => $number,
                'title' => $module['module_title'],
                'lesson_count' => $lessonCount,
                'completed_count' => $completedInModule,
                'progress_percent' => $lessonCount ? (int) round($completedInModule / $lessonCount * 100) : 0,
                'unlocked' => $freeNavigation || $this->isModuleUnlocked($number, $completedIds),
                'completed' => $lessonCount > 0 && $completedInModule === $lessonCount,
                'resume_lesson' => $this->resumeLessonIndex($module, $completedIds),
            ];
        }

        return view('app.pages.course.index', [
            'hasCourse' => !empty($moduleStates),
            'courseTitle' => Course::where('is_active', true)->value('title'),
            'moduleStates' => $moduleStates,
            'totalLessons' => $totalLessons,
            'totalCompleted' => $totalCompleted,
            'overallPercent' => $totalLessons ? (int) round($totalCompleted / $totalLessons * 100) : 0,
            'courseComplete' => $freeNavigation,
        ]);
    }

    public function module(int $module)
    {
        $moduleData = CourseContent::module($module);
        abort_unless($moduleData, 404);

        $completedIds = $this->completedLessonIds();
        $totalLessons = CourseContent::totalLessons();
        $freeNavigation = $totalLessons > 0 && count($completedIds) >= $totalLessons;
        $moduleUnlocked = $freeNavigation || $this->isModuleUnlocked($module, $completedIds);

        $lessons = [];
        foreach ($moduleData['slides'] as $slide) {
            $lessons[] = $this->decorateLesson($module, $slide, $completedIds, $freeNavigation);
        }

        return view('app.pages.course.module', [
            'moduleNumber' => $module,
            'moduleTitle' => $moduleData['module_title'],
            'lessons' => $lessons,
            'moduleUnlocked' => $moduleUnlocked,
            'resumeLesson' => $this->resumeLessonIndex($moduleData, $completedIds),
            'prevModule' => CourseContent::previousModule($module),
            'nextModule' => CourseContent::nextModule($module),
        ]);
    }

    public function lesson(int $module, int $lesson)
    {
        $moduleData = CourseContent::module($module);
        abort_unless($moduleData, 404);

        $slide = CourseContent::lesson($module, $lesson);
        abort_unless($slide, 404);

        $completedIds = $this->completedLessonIds();
        $totalLessons = CourseContent::totalLessons();
        $freeNavigation = $totalLessons > 0 && count($completedIds) >= $totalLessons;
        $unlocked = $freeNavigation || $this->isLessonUnlocked($module, $lesson, $completedIds);

        $lessonCount = CourseContent::lessonCount($module);
        $prev = $lesson > 1 ? $lesson - 1 : null;
        $next = $lesson < $lessonCount ? $lesson + 1 : null;

        $prevModule = $prev ? null : CourseContent::previousModule($module);
        $nextModule = $next ? null : CourseContent::nextModule($module);

        $navModules = [];
        foreach (CourseContent::modules() as $number => $m) {
            $navLessons = [];
            foreach ($m['slides'] as $s) {
                $navLessons[] = $this->decorateLesson($number, $s, $completedIds, $freeNavigation);
            }
            $navModules[] = [
                'number' => $number,
                'title' => $m['module_title'],
                'unlocked' => $freeNavigation || $this->isModuleUnlocked($number, $completedIds),
                'lessons' => $navLessons,
            ];
        }

        return view('app.pages.course.lesson', [
            'moduleNumber' => $module,
            'moduleTitle' => $moduleData['module_title'],
            'lesson' => $slide,
            'lessonIndex' => $lesson,
            'lessonCount' => $lessonCount,
            'unlocked' => $unlocked,
            'prev' => $prev,
            'next' => $next,
            'prevModule' => $prevModule,
            'nextModule' => $nextModule,
            'isCompleted' => in_array($slide['id'], $completedIds, true),
            'navModules' => $navModules,
        ]);
    }

    public function markComplete(int $module, int $lesson)
    {
        abort_unless(CourseContent::module($module), 404);
        $slide = CourseContent::lesson($module, $lesson);
        abort_unless($slide, 404);

        CourseLessonProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'course_lesson_id' => $slide['id']],
            ['completed_at' => now()]
        );

        $lessonCount = CourseContent::lessonCount($module);

        if ($lesson < $lessonCount) {
            return redirect()->route('course.lesson', [$module, $lesson + 1]);
        }

        $nextModule = CourseContent::nextModule($module);

        if ($nextModule) {
            return redirect()->route('course.lesson', [$nextModule, 1]);
        }

        return redirect()->route('course.certificate');
    }

    public function certificate()
    {
        $totalLessons = CourseContent::totalLessons();
        $completedIds = $this->completedLessonIds();
        $isComplete = $totalLessons > 0 && count($completedIds) >= $totalLessons;

        $course = Course::where('is_active', true)->first();
        $completionDate = now()->format('F j, Y');
        $template = \App\Models\CertificateTemplate::current();

        $data = [
            'name' => Auth::user()->name ?? 'Student Name',
            'course' => $course->title ?? '',
            'lessons' => $totalLessons,
            'date' => $completionDate,
        ];

        return view('app.pages.course.certificate', [
            'isComplete' => $isComplete,
            'completedCount' => count($completedIds),
            'totalLessons' => $totalLessons,
            'completionDate' => $completionDate,
            'template' => $template,
            'certData' => $data,
        ]);
    }

    public function downloadCertificatePdf()
    {
        $totalLessons = CourseContent::totalLessons();
        $completedIds = $this->completedLessonIds();
        $isComplete = $totalLessons > 0 && count($completedIds) >= $totalLessons;

        abort_unless($isComplete, 403, 'Certificate not available until the course is completed.');

        $course = Course::where('is_active', true)->first();
        $completionDate = now()->format('F j, Y');
        $template = \App\Models\CertificateTemplate::current();

        $data = [
            'name' => Auth::user()->name ?? 'Student Name',
            'course' => $course->title ?? '',
            'lessons' => $totalLessons,
            'date' => $completionDate,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('app.pages.course.certificate_pdf', [
            'accent' => $template->accent_color ?: '#14b8a6',
            'eyebrow' => $template->render($template->cert_eyebrow, $data),
            'title' => $template->render($template->cert_title, $data),
            'intro' => $template->render($template->cert_intro, $data),
            'name' => $data['name'],
            'body' => $template->render($template->cert_body, $data),
            'disclaimer' => $template->render($template->cert_disclaimer, $data),
            'date' => $completionDate,
            'issuerName' => $template->issuer_name,
            'issuerEmail' => $template->issuer_email,
        ])->setPaper('a4', 'landscape');

        $filename = 'certificate-' . \Illuminate\Support\Str::slug($data['name']) . '.pdf';

        return $pdf->download($filename);
    }

    // Testing tool only — lets you replay the course without losing purchased access.
    public function resetProgress()
    {
        CourseLessonProgress::where('user_id', Auth::id())->delete();

        return redirect()->route('course.index');
    }

    // Testing tool only — simulates access expiry so the purchase flow can be re-tested.
    public function removeAccess()
    {
        $course = Course::where('is_active', true)->first();

        if ($course) {
            CoursePurchase::where('user_id', Auth::id())->where('course_id', $course->id)->delete();
        }

        return redirect()->route('course.checkout');
    }

    protected function completedLessonIds(): array
    {
        if (!Auth::check()) {
            return [];
        }

        return CourseLessonProgress::where('user_id', Auth::id())->pluck('course_lesson_id')->all();
    }

    /**
     * The lesson index to resume this module at — the first lesson (in order)
     * not yet completed. Falls back to lesson 1 once every lesson is complete
     * (there's nothing left to resume, so "Review" starts from the top).
     */
    protected function resumeLessonIndex(array $moduleData, array $completedIds): int
    {
        foreach ($moduleData['slides'] as $slide) {
            if (!in_array($slide['id'], $completedIds, true)) {
                return (int) $slide['index'];
            }
        }

        return 1;
    }

    protected function completedCountInModule(int $module, array $completedIds): int
    {
        $moduleData = CourseContent::module($module);

        if (!$moduleData) {
            return 0;
        }

        $lessonIds = array_column($moduleData['slides'], 'id');

        return count(array_intersect($lessonIds, $completedIds));
    }

    protected function isModuleUnlocked(int $module, array $completedIds): bool
    {
        $prevModule = CourseContent::previousModule($module);

        if (!$prevModule) {
            return true;
        }

        $prevLessonCount = CourseContent::lessonCount($prevModule);

        return $prevLessonCount > 0 && $this->completedCountInModule($prevModule, $completedIds) >= $prevLessonCount;
    }

    protected function isLessonUnlocked(int $module, int $lesson, array $completedIds): bool
    {
        if (!$this->isModuleUnlocked($module, $completedIds)) {
            return false;
        }

        if ($lesson === 1) {
            return true;
        }

        $prevSlide = CourseContent::lesson($module, $lesson - 1);

        return $prevSlide && in_array($prevSlide['id'], $completedIds, true);
    }

    protected function decorateLesson(int $module, array $slide, array $completedIds, bool $freeNavigation): array
    {
        $slide['module_number'] = $module;
        $slide['unlocked'] = $freeNavigation || $this->isLessonUnlocked($module, (int) $slide['index'], $completedIds);
        $slide['completed'] = in_array($slide['id'], $completedIds, true);

        return $slide;
    }
}
