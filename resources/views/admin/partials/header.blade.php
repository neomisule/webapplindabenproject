<header class="app-header sticky" id="header">
    <div class="main-header-container container-fluid">
        <div class="header-content-left">
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{ route('admin.dashboard') }}" class="header-logo">
                        <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="desktop-logo">
                        <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="toggle-logo">
                        <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="toggle-white">
                        <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="desktop-white">
                    </a>
                </div>
            </div>
            <div class="header-element mx-lg-0 mx-2">
                <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
            </div>
        </div>
        <ul class="header-content-right">
            <li class="header-element dropdown">
                <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div>
                            @if(auth('web')->user()->profile_picture)
                                <img src="{{ asset('storage/'.auth('web')->user()->profile_picture) }}" alt="img" class="avatar custom-header-avatar avatar-rounded">
                            @else
                                <div class="avatar custom-header-avatar avatar-rounded bg-primary text-white d-flex align-items-center justify-content-center">
                                    {{ substr(auth('web')->user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="ms-2 d-none d-sm-block">
                            <span class="d-block fw-medium">{{ auth('web')->user()->name }}</span>
                        </div>
                    </div>
                </a>
                <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                    <li>
                        <div class="dropdown-item text-center border-bottom">
                            <div class="mb-2">
                                @if(auth('web')->user()->profile_picture)
                                    <img src="{{ asset('storage/'.auth('web')->user()->profile_picture) }}" alt="img" width="80" class="rounded-circle">
                                @else
                                    <div class="avatar avatar-lg rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto">
                                        {{ substr(auth('web')->user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <span class="fw-medium d-block">{{ auth('web')->user()->name }}</span>
                            <small class="text-muted d-block">{{ auth('web')->user()->email }}</small>
                        </div>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile') }}"><i class="ri-user-line lh-1 p-1 rounded-circle bg-primary-transparent text-primary me-2 fs-14"></i>Profile</a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}"><i class="ri-door-lock-line lh-1 p-1 rounded-circle bg-danger-transparent text-danger me-2 fs-14"></i>Log Out</a></li>
                </ul>
            </li>
        </ul>
    </div>
</header>
