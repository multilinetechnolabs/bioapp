@extends('layouts.modern')

@section('page-title', 'Course Pricing')

@php
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/course.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="modern-main-content modern-main-content--fluid">
    <div class="course-shell">
        <div class="course-container" style="max-width:520px;">
            <div class="course-panel" style="text-align:center;">
                <span class="course-demo-flag">Dummy payment &mdash; UI/UX demo only</span>

                @if ($status === 'success' && $alreadyPaid)
                    <div class="course-locked-state__icon" style="background:#d1fae5;border-color:#6ee7b7;color:#065f46;"><i class="fa fa-check" aria-hidden="true"></i></div>
                    <h1 class="course-hero__title" style="font-size:1.4rem;">Payment Successful</h1>
                    <p class="course-hero__subtitle" style="margin:0 auto 20px;">You now have 1 year of course access.</p>
                    <a href="{{ route('course.index') }}" class="course-btn course-btn--primary course-btn--block">Continue to Course</a>

                @elseif ($status === 'failed')
                    <div class="course-locked-state__icon" style="background:#fee2e2;border-color:#fca5a5;color:#b91c1c;"><i class="fa fa-times" aria-hidden="true"></i></div>
                    <h1 class="course-hero__title" style="font-size:1.4rem;">Payment Failed / Cancelled</h1>
                    <p class="course-hero__subtitle" style="margin:0 auto 20px;">No charge was made. You can try again.</p>
                    <a href="{{ route('course.checkout') }}" class="course-btn course-btn--primary course-btn--block">Try Again</a>

                @else
                    <h1 class="course-hero__title" style="font-size:1.5rem;">Biomagnetism Certification Course</h1>
                    <p class="course-hero__subtitle" style="margin:0 auto 20px;">Full access to the certification course, plus Body Scan &amp; Chakra Scan tools, for 1 year.</p>
                    <div style="font-size:2.4rem;font-family:var(--course-font-serif);color:var(--course-primary-dark);margin:10px 0;">
                        $197 <span style="font-size:.9rem;color:var(--course-ink-soft);">one-time</span>
                    </div>
                    <ul style="text-align:left;max-width:320px;margin:18px auto;padding:0;list-style:none;color:var(--course-ink-soft);font-size:.88rem;line-height:1.9;">
                        <li>&#10003; All 9 modules &amp; certificate</li>
                        <li>&#10003; Body Scan &amp; Chakra Scan access</li>
                        <li>&#10003; 1 year of full access</li>
                    </ul>

                    <div style="display:flex;align-items:flex-start;gap:10px;text-align:left;background:#f0fdfa;border:1px solid #99f6e4;border-radius:10px;padding:12px 14px;margin:0 auto 18px;max-width:340px;">
                        <i class="fa fa-shield" aria-hidden="true" style="color:#0f766e;margin-top:2px;"></i>
                        <div style="font-size:.82rem;color:#0f766e;">
                            <strong>14-Day Guarantee</strong><br>
                            Complete your first 2 modules — if the course isn't for you, request a full refund within 14 days of purchase.
                        </div>
                    </div>

                    @if ($alreadyPaid)
                        <a href="{{ route('course.index') }}" class="course-btn course-btn--primary course-btn--block">Go to Course</a>
                    @else
                        <div style="text-align:left;background:#f8fafc;border:1px solid var(--course-border);border-radius:10px;padding:12px 14px;margin-bottom:14px;font-size:.76rem;color:var(--course-ink-soft);max-height:120px;overflow-y:auto;">
                            <strong>Course Enrollment Agreement</strong><br>
                            All course materials (text, diagrams, videos, and workbooks) are licensed for individual educational use only. Unauthorized reproduction, distribution, or teaching of this proprietary content is strictly prohibited.
                            &copy; {{ date('Y') }} Anew Avenue Biomagnetism. All rights reserved. For personal educational use only.
                        </div>
                        <div style="text-align:left;margin-bottom:16px;">
                            <p style="font-size:.78rem;font-weight:700;color:var(--course-ink-soft);margin:0 0 8px;">FAQ — Course Enrollment Agreement</p>
                            <details style="font-size:.82rem;margin-bottom:6px;">
                                <summary style="cursor:pointer;font-weight:600;">What does "personal educational use only" mean?</summary>
                                <p style="color:var(--course-ink-soft);margin:6px 0 0;">The course materials (text, diagrams, videos, workbooks) are licensed to you individually. You can use them for your own learning and practice, but you can't copy, resell, or hand them off to someone else.</p>
                            </details>
                            <details style="font-size:.82rem;margin-bottom:6px;">
                                <summary style="cursor:pointer;font-weight:600;">Can I teach or share this content with others?</summary>
                                <p style="color:var(--course-ink-soft);margin:6px 0 0;">No. Teaching, re-branding, or redistributing the course content to other people is not permitted under the agreement.</p>
                            </details>
                            <details style="font-size:.82rem;margin-bottom:6px;">
                                <summary style="cursor:pointer;font-weight:600;">Does completing the course give me a license to practice or teach?</summary>
                                <p style="color:var(--course-ink-soft);margin:6px 0 0;">No. Your completion certificate confirms you finished the course and app training — it is not a state-issued medical license or teaching authorization.</p>
                            </details>
                            <details style="font-size:.82rem;margin-bottom:6px;">
                                <summary style="cursor:pointer;font-weight:600;">What if I change my mind after purchasing?</summary>
                                <p style="color:var(--course-ink-soft);margin:6px 0 0;">Complete your first 2 modules — if it's not for you, you can request a full refund within 14 days of purchase (see the guarantee above).</p>
                            </details>
                            <details style="font-size:.82rem;">
                                <summary style="cursor:pointer;font-weight:600;">Do I need to agree again if I retake the course later?</summary>
                                <p style="color:var(--course-ink-soft);margin:6px 0 0;">No — agreeing once at purchase covers your full 1 year of access to this course.</p>
                            </details>
                        </div>

                        <form action="{{ route('course.checkout.pay') }}" method="POST" style="margin-bottom:8px;">
                            @csrf
                            <div style="text-align:left;margin-bottom:12px;font-size:.85rem;">
                                <label style="display:flex;align-items:flex-start;gap:8px;">
                                    <input type="checkbox" name="agree_tos" required style="margin-top:3px;">
                                    <span>I have read and agree to the Course Enrollment Agreement and Terms of Service above.</span>
                                </label>
                            </div>
                            <button type="submit" class="course-btn course-btn--primary course-btn--block">Pay $197 (Simulate Success)</button>
                        </form>
                        <form action="{{ route('course.checkout.fail') }}" method="POST">
                            @csrf
                            <button type="submit" class="course-btn course-btn--outline course-btn--block">Cancel Payment (Simulate Failure)</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
