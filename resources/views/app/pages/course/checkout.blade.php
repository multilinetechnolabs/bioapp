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

                @if (!$course)
                    <div class="course-locked-state__icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>
                    <h1 class="course-hero__title" style="font-size:1.4rem;">No Course Available Yet</h1>
                    <p class="course-hero__subtitle" style="margin:0 auto 20px;">We're still setting things up here. Please check back soon.</p>
                    <a href="{{ route('app.dashboard') }}" class="course-btn course-btn--outline">Back to Dashboard</a>

                @elseif ($status === 'success' && $alreadyPaid)
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
                    @if ($hasExpired)
                        <div style="background:#fee2e2;border:1px solid #fca5a5;color:#b91c1c;border-radius:10px;padding:10px 14px;margin-bottom:16px;font-size:.85rem;">
                            Your previous 1-year access has expired. Purchase again to continue.
                        </div>
                    @endif

                    <h1 class="course-hero__title" style="font-size:1.5rem;">{{ $course->title }}</h1>
                    <p class="course-hero__subtitle" style="margin:0 auto 20px;">{{ $course->description }}</p>
                    <div style="font-size:2.4rem;font-family:var(--course-font-serif);color:var(--course-primary-dark);margin:10px 0;">
                        ${{ number_format($course->price, 0) }} <span style="font-size:.9rem;color:var(--course-ink-soft);">one-time</span>
                    </div>
                    <ul style="text-align:left;max-width:320px;margin:18px auto;padding:0;list-style:none;color:var(--course-ink-soft);font-size:.88rem;line-height:1.9;">
                        <li>&#10003; All {{ $course->modules()->count() }} modules &amp; certificate</li>
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

                        <form id="courseCheckoutForm">
                            @csrf
                            <div style="text-align:left;margin-bottom:12px;font-size:.85rem;">
                                <label style="display:flex;align-items:flex-start;gap:8px;">
                                    <input type="checkbox" id="courseAgreeTos" required style="margin-top:3px;">
                                    <span>I have read and agree to the Course Enrollment Agreement and Terms of Service above.</span>
                                </label>
                            </div>
                            <button type="submit" class="course-btn course-btn--primary course-btn--block">Pay ${{ number_format($course->price, 0) }} &amp; Enroll</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</main>

@if ($course && !$alreadyPaid && $status !== 'failed')
    @push('scripts')
    <script src="https://checkout.freemius.com/js/v1/"></script>
    <script>
    (function () {
        var form = document.getElementById('courseCheckoutForm');
        if (!form) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            var agree = document.getElementById('courseAgreeTos');
            if (!agree.checked) {
                alert('Please agree to the Course Enrollment Agreement before continuing.');
                return;
            }

            try {
                const response = await fetch("{{ route('course.checkout.freemiusInit') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (!data.success) {
                    alert(data.message || 'Something went wrong while preparing the payment.');
                    return;
                }

                if (!data.product_id || !data.plan_id || !data.public_key) {
                    alert('Payment configuration is incomplete.');
                    return;
                }

                const checkoutData = {
                    product_id: data.product_id,
                    plan_id: data.plan_id,
                    public_key: data.public_key,
                    image: data.image,
                    ...(data.sandbox && { sandbox: { token: data.sandbox_token, ctx: data.sandbox_ctx } }),
                };

                const handler = new FS.Checkout(checkoutData);

                handler.open({
                    name: data.purchase_name,
                    licenses: data.licenses,
                    user_email: data.email,
                    readonly_user: true,
                    purchaseCompleted: async (response) => {
                        try {
                            await fetch("{{ route('course.checkout.freemiusSuccess') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ transaction_id: data.transaction_id, status: 'success', response: response })
                            });
                        } catch (error) {
                            console.error('Course success update failed:', error);
                        }
                        window.location.href = "{{ route('course.checkout', ['status' => 'success']) }}";
                    },
                    cancel: async () => {
                        try {
                            await fetch("{{ route('course.checkout.freemiusFailed') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ transaction_id: data.transaction_id, status: 'failed' })
                            });
                        } catch (error) {
                            console.error('Course failure update failed:', error);
                        }
                        window.location.href = "{{ route('course.checkout', ['status' => 'failed']) }}";
                    }
                });
            } catch (error) {
                console.error('Course Freemius checkout failed:', error);
                alert('Unable to open the payment window right now. Please try again.');
            }
        });
    })();
    </script>
    @endpush
@endif
@endsection
