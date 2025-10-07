    <header class="app-header sticky" id="header">
        <div class="main-header-container container-fluid">
            <div class="header-content-left">
                <div class="header-element">
                    <div class="horizontal-logo">
                        <a href="{{ auth()->user()->hasRole('staff') ? route('staff.dashboard') : route('volunteer.dashboard') }}" class="header-logo">
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
                @auth
                    @if(auth()->user()->hasRole('staff'))
                        <li class="header-element">
                            @if(auth()->user()->hasRole('volunteer'))
                                <a href="{{ route('volunteer.dashboard') }}" class="header-link "style="background-color: #1b5e20; color: #fff; padding: 0.375rem 0.75rem; border-radius: 4px;">
                                    <i class="ri-user-star-line me-1"></i> Volunteer Panel
                                </a>
                            @elseif(auth()->user()->volunteer_requested_at )
                                 <span class="header-link" style="background-color: #ffc107; color: #000; padding: 0.375rem 0.75rem; border-radius: 4px;">
                                <i class="ri-time-line me-1"></i> Request Pending
                            </span>
                            @else
                                <a href="{{ route('become.volunteer') }}" class="header-link "style="background-color: #1b5e20; color: #fff; padding: 0.375rem 0.75rem; border-radius: 4px;">
                                    <i class="ri-user-add-line me-1"></i> Become Volunteer
                                </a>
                            @endif
                        </li>
                    @endif
                    <li class="header-element dropdown">
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div>
                                    @if(auth()->user()->profile_picture)
                                        <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" alt="img" class="avatar custom-header-avatar avatar-rounded">
                                    @else
                                        <div class="avatar custom-header-avatar avatar-rounded bg-primary text-white d-flex align-items-center justify-content-center">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ms-2 d-none d-sm-block">
                                    <span class="d-block fw-medium">{{ auth()->user()->name }}</span>
                                    <small class="d-block text-muted">
                                        @if(auth()->user()->hasRole('admin'))
                                            Administrator
                                        @elseif(auth()->user()->hasRole('staff'))
                                            Staff Member
                                            @if(auth()->user()->hasRole('volunteer'))
                                                + Volunteer
                                            @endif
                                        @elseif(auth()->user()->hasRole('volunteer'))
                                            Volunteer
                                        @else
                                            User
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </a>
                        <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                            <li>
                                <div class="dropdown-item text-center border-bottom">
                                    <div class="mb-2">
                                        @if(auth()->user()->profile_picture)
                                            <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" alt="img" width="80" class="rounded-circle">
                                        @else
                                            <div class="avatar avatar-lg rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="fw-medium d-block">{{ auth()->user()->name }}</span>
                                    <small class="text-muted d-block">{{ auth()->user()->email }}</small>
                                </div>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('role.profile') }}"><i class="ri-user-line lh-1 p-1 rounded-circle bg-primary-transparent text-primary me-2 fs-14"></i>Profile</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"><i class="ri-door-lock-line lh-1 p-1 rounded-circle bg-danger-transparent text-danger me-2 fs-14"></i>Log Out</a></li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </header>
