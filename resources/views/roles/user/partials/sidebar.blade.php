<aside class="app-sidebar sticky" id="sidebar">
    <div class="main-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
            <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="toggle-white">
            <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="desktop-white">
        </a>
    </div>
    <div class="main-sidebar" id="sidebar-scroll">
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">

                <li class="slide ">
                    <a href="{{ route('user.dashboard') }}" class="side-menu__item ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 side-menu__icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span class="side-menu__label">Dashboards</span>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
