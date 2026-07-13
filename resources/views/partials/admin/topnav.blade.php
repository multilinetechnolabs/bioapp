@php $currentPath = request()->path(); @endphp

<aside class="admin-sidebar" id="adminSidebar">

    <div class="admin-sidebar__header">
        <img src="/images/iconimages/load.png" alt="{{ config('app.name') }}" class="admin-sidebar__logo">
        <span class="admin-sidebar__brand">Admin</span>
        <button class="admin-sidebar__close" id="adminSidebarClose" type="button" aria-label="Close sidebar">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="admin-nav">

        <a href="{{ url('/admin') }}"
           class="admin-nav__link {{ $currentPath === 'admin' ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>

        <div class="admin-nav__section">Pairs</div>

        <a href="{{ url('/admin/pairs/bio') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'pairs/bio') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/></svg>
            Bio Pairs
        </a>

        <a href="{{ url('/admin/pairs/chakra') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'pairs/chakra') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 100 100" stroke-width="1.2"><circle cx="50" cy="50" r="32"/><circle cx="50" cy="50" r="16"/><circle cx="66" cy="50" r="16"/><circle cx="58" cy="36.1" r="16"/><circle cx="42" cy="36.1" r="16"/><circle cx="34" cy="50" r="16"/><circle cx="42" cy="63.9" r="16"/><circle cx="58" cy="63.9" r="16"/></svg>
            Chakra Pairs
        </a>

        <a href="{{ url('/admin/dr_goiz_pairs') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'dr_goiz_pairs') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Protocol Pairs
        </a>

        <div class="admin-nav__section">Content</div>

        <a href="{{ url('/admin/course') }}"
           class="admin-nav__link {{ $currentPath === 'admin/course' || str_starts_with($currentPath, 'admin/course/') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v7m-7-4.5V15c0 1.657 3.134 3 7 3s7-1.343 7-3v-2.5"/></svg>
            Course Config
        </a>

        <a href="{{ url('/admin/certificate') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'admin/certificate') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Certificate Template
        </a>

        <a href="{{ url('/admin/media') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'admin/media') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.899L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
            Media Files
        </a>

        <a href="{{ url('/admin/playlist') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'admin/playlist') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h10M15 14l5 3-5 3V14z"/></svg>
            Playlists
        </a>

        <div class="admin-nav__section">Scanning</div>

        <a href="{{ url('/admin/model_labels/body_scan') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'body_scan') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Body Scan Labels
        </a>

        <a href="{{ url('/admin/model_labels/chakra_scan') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'chakra_scan') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 100 100" stroke-width="1.2"><circle cx="50" cy="50" r="32"/><circle cx="50" cy="50" r="16"/><circle cx="66" cy="50" r="16"/><circle cx="58" cy="36.1" r="16"/><circle cx="42" cy="36.1" r="16"/><circle cx="34" cy="50" r="16"/><circle cx="42" cy="63.9" r="16"/><circle cx="58" cy="63.9" r="16"/></svg>
            Chakra Scan Labels
        </a>

        <div class="admin-nav__section">Marketing</div>

        <a href="{{ route('admin.plans') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'admin/plans') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
            Plans
        </a>

        <a href="{{ route('admin.subscriptions') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'admin/subscriptions') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Subscriptions
        </a>

        <a href="{{ route('admin.course_subscriptions') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'admin/course-subscriptions') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v7m-7-4.5V15c0 1.657 3.134 3 7 3s7-1.343 7-3v-2.5"/></svg>
            Course Subscriptions
        </a>

        <div class="admin-nav__section">Users</div>

        <a href="{{ url('/admin/users') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'admin/users') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Users
        </a>

        <div class="admin-nav__section">Tools</div>

        <a href="{{ url('/admin/email') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'admin/email') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Compose Email
        </a>

        @php $contactUnread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
        <a href="{{ route('admin.contact') }}"
           class="admin-nav__link {{ str_contains($currentPath, 'admin/contact') ? 'active' : '' }}"
           style="justify-content: space-between;">
            <span style="display:flex;align-items:center;gap:0.75rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Contact Messages
            </span>
            @if($contactUnread > 0)
                <span style="background:#dc2626;color:#fff;font-size:0.65rem;font-weight:700;border-radius:999px;min-width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">{{ $contactUnread }}</span>
            @endif
        </a>

    </nav>

</aside>
