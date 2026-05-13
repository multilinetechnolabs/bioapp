<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('partials.shared.meta')
    @include('partials.shared.link_fonts')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app/homepage.css') }}" rel="stylesheet">

    <title>{{ env('APP_TITLE') }} | An App for Therapist and Practitioners of Biomagnetism</title>
</head>

<body>
    <nav class="hp-topnav">
        <a href="/" class="hp-topnav__brand">
            <img src="{{ asset('images/iconimages/load.png') }}" alt="{{ env('APP_TITLE') }}" class="hp-topnav__logo">
            <span>{{ env('APP_TITLE') }}</span>
        </a>
        <div class="hp-topnav__links">
            <a href="/pricing" class="hp-topnav__link">Pricing</a>
            <a href="#contact" class="hp-topnav__link">Contact Us</a>
            @guest
                <a href="{{ route('login') }}" class="hp-topnav__btn">Login</a>
            @else
                <a href="{{ route('app.dashboard') }}" class="hp-topnav__btn">Dashboard</a>
            @endguest
        </div>
    </nav>

    <div class="main">
        <div class="header">
            <div class="logo-left">
                <img src="{{ asset('images/homepage/flamingo.png') }}" alt="{{ env('APP_TITLE') }}" />
            </div>

            <div class="header-middle">
                <h1>{{ env('APP_TITLE') }}</h1>
                <h4>Discover a natural shift of healing with biomagnetism</h4>
            </div>

            <div class="logo-right">
                <img src="{{ asset('images/homepage/spiral.png') }}" alt="{{ env('APP_TITLE') }}" />
            </div>
        </div>
        <div class="main-menu">
            <ul class="nav">
                <li>
                    <a href="{{ route('app.dashboard') }}" class="active">
                        <img src="{{ asset('images/homepage/learn-more.png') }}"
                            alt="{{ env('APP_TITLE') }} | Learn more about Biomagnetism " />
                        <span>Learn More</span>
                    </a>
                </li>
                <li>
                    <a href="{{ Auth::user() ? route('app.bodyscan') : route('app.bodyscan.info') }}">
                        <img src="{{ asset('images/homepage/bodyscan.png') }}"
                            alt="{{ env('APP_TITLE') }} | Body Scan" />
                        <span>Body Scan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ Auth::user() ? route('app.chakrascan') : route('app.chakrascan.info') }}">
                        <img src="{{ asset('images/homepage/chakrascan.png') }}"
                            alt="{{ env('APP_TITLE') }} | Chakra Scan" />
                        <span>Chakra Scan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ Auth::user() ? route('app.data_cache') : route('app.data_cache.info') }}">
                        <img src="{{ asset('images/homepage/dashboard.png') }}"
                            alt="{{ env('APP_TITLE') }} | Data Cache" />
                        <span>Data Cache</span>
                    </a>
                </li>
                <li>
                    <a href="{{ Auth::user() ? route('app.bioconnect') : route('app.bioconnect.info') }}">
                        <img src="{{ asset('images/homepage/bioconnect.png') }}"
                            alt="{{ env('APP_TITLE') }} | Bio Connect" />
                        <span>Bio Connect</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.dr_goiz_pairs') }}">
                        <img src="{{ asset('images/homepage/more.png') }}"
                            alt="{{ env('APP_TITLE') }} | FREE PROTOCOL PAIRS" />
                        <span>FREE PROTOCOL PAIRS</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <section id="contact" class="hp-contact">
        <div class="hp-contact__inner">
            <h2 class="hp-contact__title">Contact Us</h2>
            <p class="hp-contact__subtitle">Have a question or need help? We'd love to hear from you.</p>

            <div class="hp-contact__grid">
                <div class="hp-contact__info">
                    <div class="hp-contact__info-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ env('MAIL_FROM_ADDRESS', 'info@anewavenue.com') }}">{{ env('MAIL_FROM_ADDRESS', 'info@anewavenue.com') }}</a>
                    </div>
                    <div class="hp-contact__info-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        <span>{{ env('APP_URL') }}</span>
                    </div>
                    <div class="hp-contact__info-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Mon – Fri, 9 AM – 5 PM</span>
                    </div>
                    <p class="hp-contact__blurb">
                        Whether you're a practitioner, therapist, or just curious about biomagnetism — reach out and we'll get back to you as soon as possible.
                    </p>
                </div>

                <form class="hp-contact__form" action="mailto:{{ env('MAIL_FROM_ADDRESS', 'info@anewavenue.com') }}" method="get" enctype="text/plain">
                    <input type="text"  name="name"    placeholder="Your Name"    class="hp-contact__input" required>
                    <input type="email" name="email"   placeholder="Your Email"   class="hp-contact__input" required>
                    <input type="text"  name="subject" placeholder="Subject"      class="hp-contact__input">
                    <textarea           name="message" placeholder="Your Message" class="hp-contact__textarea" rows="5" required></textarea>
                    <button type="submit" class="hp-contact__submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    @include('partials.shared.foot')
</body>

</html>
