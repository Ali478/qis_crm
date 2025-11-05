<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Quick International Shipping Company'))</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- RTL Support for Arabic -->
    @if(app()->getLocale() == 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @endif

    @stack('styles')
</head>
<body data-theme="{{ session('theme', 'light') }}">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header p-3">
                <div class="d-flex flex-column align-items-center text-center">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=1e40af&color=fff&size=80"
                         alt="User"
                         class="rounded-circle shadow mb-2"
                         width="80"
                         height="80">
                    <h6 class="mb-0 text-white">{{ auth()->user()->name ?? 'User' }}</h6>
                </div>
            </div>

            <nav class="nav-menu">
                <ul class="list-unstyled">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="{{ __('Dashboard') }}">
                            <i class="fas fa-tachometer-alt nav-icon"></i>
                            <span>{{ __('Dashboard') }}</span>
                        </a>
                    </li>

                    <!-- Shipments -->
                    <li class="nav-item">
                        <a href="{{ route('shipments.index') }}" class="nav-link {{ request()->routeIs('shipments.*') ? 'active' : '' }}" title="{{ __('Shipments') }}">
                            <i class="fas fa-shipping-fast nav-icon"></i>
                            <span>{{ __('Shipments') }}</span>
                        </a>
                    </li>

                    <!-- Customers -->
                    <li class="nav-item">
                        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" title="{{ __('Customers') }}">
                            <i class="fas fa-users nav-icon"></i>
                            <span>{{ __('Customers') }}</span>
                        </a>
                    </li>

                    <!-- Invoices -->
                    <li class="nav-item">
                        <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" title="{{ __('Invoices') }}">
                            <i class="fas fa-file-invoice nav-icon"></i>
                            <span>{{ __('Invoices') }}</span>
                        </a>
                    </li>

                    <!-- Finance Management -->
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('finance.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#financeMenu" title="{{ __('Finance') }}">
                            <i class="fas fa-chart-line nav-icon"></i>
                            <span>{{ __('Finance') }}</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <ul class="collapse list-unstyled ps-3 {{ request()->routeIs('finance.*') ? 'show' : '' }}" id="financeMenu">
                            <li><a href="{{ route('finance.index') }}" class="nav-link {{ request()->routeIs('finance.index') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> {{ __('Overview') }}
                            </a></li>
                            <li><a href="{{ route('finance.revenue') }}" class="nav-link {{ request()->routeIs('finance.revenue') ? 'active' : '' }}">
                                <i class="fas fa-arrow-up text-success"></i> {{ __('Revenue') }}
                            </a></li>
                            <li><a href="{{ route('finance.expenses') }}" class="nav-link {{ request()->routeIs('finance.expenses*') ? 'active' : '' }}">
                                <i class="fas fa-arrow-down text-danger"></i> {{ __('Expenses') }}
                            </a></li>
                            <li><a href="{{ route('finance.reports') }}" class="nav-link {{ request()->routeIs('finance.reports') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar"></i> {{ __('Reports') }}
                            </a></li>
                        </ul>
                    </li>

                    <!-- Staff Management -->
                    @if(session('logged_in'))
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#staffMenu" title="{{ __('Staff') }}">
                            <i class="fas fa-user-tie nav-icon"></i>
                            <span>{{ __('Staff') }}</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <ul class="collapse list-unstyled ps-3 {{ request()->routeIs('staff.*') ? 'show' : '' }}" id="staffMenu">
                            <li><a href="{{ route('staff.index') }}" class="nav-link {{ request()->routeIs('staff.index') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> {{ __('Staff List') }}
                            </a></li>
                            <li><a href="{{ route('staff.create') }}" class="nav-link {{ request()->routeIs('staff.create') ? 'active' : '' }}">
                                <i class="fas fa-user-plus"></i> {{ __('Add Staff') }}
                            </a></li>
                            <li><a href="{{ route('staff.salaries') }}" class="nav-link {{ request()->routeIs('staff.salaries') ? 'active' : '' }}">
                                <i class="fas fa-money-bill"></i> {{ __('Salaries') }}
                            </a></li>
                            <li><a href="{{ route('staff.roles') }}" class="nav-link {{ request()->routeIs('staff.roles') ? 'active' : '' }}">
                                <i class="fas fa-user-tag"></i> {{ __('Roles & Permissions') }}
                            </a></li>
                        </ul>
                    </li>
                    @endif

                    <!-- Reports -->
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#reportMenu" title="{{ __('Reports') }}">
                            <i class="fas fa-chart-bar nav-icon"></i>
                            <span>{{ __('Reports') }}</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <ul class="collapse list-unstyled ps-3 {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reportMenu">
                            <li><a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> {{ __('Overview') }}
                            </a></li>
                            <li><a href="{{ route('reports.shipments') }}" class="nav-link {{ request()->routeIs('reports.shipments') ? 'active' : '' }}">
                                <i class="fas fa-shipping-fast"></i> {{ __('Shipments') }}
                            </a></li>
                            <li><a href="{{ route('reports.financial') }}" class="nav-link {{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie"></i> {{ __('Financial') }}
                            </a></li>
                            <li><a href="{{ route('reports.customers') }}" class="nav-link {{ request()->routeIs('reports.customers') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> {{ __('Customers') }}
                            </a></li>
                            <li><a href="{{ route('reports.staff') }}" class="nav-link {{ request()->routeIs('reports.staff') ? 'active' : '' }}">
                                <i class="fas fa-user-tie"></i> {{ __('Staff Performance') }}
                            </a></li>
                        </ul>
                    </li>

                    <!-- Settings -->
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#settingsMenu" title="{{ __('Settings') }}">
                            <i class="fas fa-cog nav-icon"></i>
                            <span>{{ __('Settings') }}</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <ul class="collapse list-unstyled ps-3 {{ request()->routeIs('settings.*') ? 'show' : '' }}" id="settingsMenu">
                            <li><a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> {{ __('Overview') }}
                            </a></li>
                            <li><a href="{{ route('settings.system') }}" class="nav-link {{ request()->routeIs('settings.system') ? 'active' : '' }}">
                                <i class="fas fa-cogs"></i> {{ __('System Settings') }}
                            </a></li>
                            <li><a href="{{ route('settings.branches') }}" class="nav-link {{ request()->routeIs('settings.branches') ? 'active' : '' }}">
                                <i class="fas fa-building"></i> {{ __('Branches') }}
                            </a></li>
                            <li><a href="{{ route('settings.dynamic-options') }}" class="nav-link {{ request()->routeIs('settings.dynamic-options*') ? 'active' : '' }}">
                                <i class="fas fa-list-alt"></i> {{ __('Dynamic Options') }}
                            </a></li>
                            <li><a href="{{ route('settings.profile') }}" class="nav-link {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
                                <i class="fas fa-user-circle"></i> {{ __('User Profile') }}
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <!-- Left side: Menu toggle and Page title -->
                    <div class="d-flex align-items-center">
                        <button class="btn btn-link sidebar-toggle p-0 me-3 d-none d-md-block">
                            <i class="fas fa-bars fa-lg"></i>
                        </button>
                        <button class="btn btn-link mobile-menu-toggle p-0 d-md-none">
                            <i class="fas fa-bars fa-lg"></i>
                        </button>
                        <h4 class="mb-0 ms-2">@yield('page-title', 'Dashboard')</h4>
                    </div>

                    <!-- Right side: All controls -->
                    <div class="d-flex align-items-center gap-2 gap-md-3">
                        <!-- Branch Selector -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-building"></i>
                                <span class="d-none d-md-inline">{{ session('current_branch_name', 'Select Branch') }}</span>
                                <span class="d-md-none">Branch</span>
                            </button>
                            <ul class="dropdown-menu shadow-lg">
                                <li><h6 class="dropdown-header">
                                    <i class="fas fa-building me-2"></i>Switch Branch
                                </h6></li>
                                <li><hr class="dropdown-divider"></li>
                                @php
                                    $branches = \DB::table('branches')->where('is_active', true)->get();
                                @endphp
                                @foreach($branches as $branch)
                                <li>
                                    <a class="dropdown-item {{ session('current_branch_id') == $branch->id ? 'active' : '' }}"
                                       href="{{ route('switch.branch', $branch->id) }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-semibold">{{ $branch->name }}</div>
                                                <small class="text-muted">{{ $branch->city }}, {{ $branch->country }}</small>
                                            </div>
                                            @if(session('current_branch_id') == $branch->id)
                                            <i class="fas fa-check text-primary"></i>
                                            @endif
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-primary" href="{{ route('settings.branches') }}">
                                        <i class="fas fa-cog me-2"></i>Manage Branches
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Language Selector -->
                        <div class="d-none d-sm-block">
                            <select class="form-select form-select-sm language-selector" style="width: auto; min-width: 100px;">
                                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇬🇧 EN</option>
                                <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>🇸🇦 AR</option>
                                <option value="zh" {{ app()->getLocale() == 'zh' ? 'selected' : '' }}>🇨🇳 ZH</option>
                            </select>
                        </div>

                        <!-- Theme Toggle -->
                        <button class="btn btn-sm btn-outline-secondary theme-toggle rounded-pill" title="Toggle theme">
                            <i class="fas fa-moon theme-icon-dark"></i>
                            <i class="fas fa-sun theme-icon-light d-none"></i>
                        </button>

                        <!-- Vertical Divider -->
                        <div class="vr d-none d-sm-block"></div>

                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="btn btn-link position-relative p-1" data-bs-toggle="dropdown" title="Notifications">
                                <i class="fas fa-bell fa-lg text-secondary"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    3
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="min-width: 300px;">
                                <li><h6 class="dropdown-header bg-light">
                                    <i class="fas fa-bell me-2"></i>Notifications
                                </h6></li>
                                <li><a class="dropdown-item py-2" href="#">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-ship text-primary me-3"></i>
                                        <div>
                                            <div class="fw-semibold">New shipment arrived</div>
                                            <small class="text-muted">2 minutes ago</small>
                                        </div>
                                    </div>
                                </a></li>
                                <li><a class="dropdown-item py-2" href="#">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-dollar-sign text-success me-3"></i>
                                        <div>
                                            <div class="fw-semibold">Payment received</div>
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                    </div>
                                </a></li>
                                <li><a class="dropdown-item py-2" href="#">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-upload text-info me-3"></i>
                                        <div>
                                            <div class="fw-semibold">Document uploaded</div>
                                            <small class="text-muted">3 hours ago</small>
                                        </div>
                                    </div>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center text-primary" href="#">
                                    <i class="fas fa-eye me-2"></i>View all notifications
                                </a></li>
                            </ul>
                        </div>

                        <!-- User Menu -->
                        <div class="dropdown">
                            <button class="btn btn-link d-flex align-items-center p-1" data-bs-toggle="dropdown" title="User menu">
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=1e40af&color=fff"
                                     alt="User" class="rounded-circle shadow-sm" width="32" height="32">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="min-width: 200px;">
                                <li class="px-3 py-2 bg-light">
                                    <div class="fw-semibold">{{ auth()->user()->name ?? 'User' }}</div>
                                    <small class="text-muted">{{ auth()->user()->email ?? 'user@example.com' }}</small>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2" href="#">
                                    <i class="fas fa-user me-2 text-primary"></i>My Profile
                                </a></li>
                                <li><a class="dropdown-item py-2" href="#">
                                    <i class="fas fa-cog me-2 text-secondary"></i>Settings
                                </a></li>
                                <li><a class="dropdown-item py-2" href="#">
                                    <i class="fas fa-life-ring me-2 text-info"></i>Support
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="mt-5 py-3 text-center text-muted">
                <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
            </footer>
        </div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay"></div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5.3 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>