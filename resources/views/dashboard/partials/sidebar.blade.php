<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            @php
                $setting = \App\Models\Setting::first();
            @endphp
            <li class="nav-item me-auto"><a class="navbar-brand" href="{{ route('dashboard.home') }}"><span
                        class="brand-logo"><img src="{{ asset($setting->logo) }}"></span>
                    <h2 class="brand-text">{{ $setting->site_name }}</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item @yield('dashboard-active')"><a class="d-flex align-items-center"
                    href="{{ route('dashboard.home') }}"><i data-feather="home"></i><span
                        class="menu-title text-truncate" data-i18n="Email">{{ __('dashboard.home') }}</span></a>
            </li>
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Apps &amp; Pages</span><i
                    data-feather="more-horizontal"></i>
            </li>

            @can('roles')
                <li class="nav-item @yield('roles-active')">
                    <a class="d-flex align-items-center" href="{{ route('dashboard.roles.index') }}">
                        <i data-feather='shield'></i>
                        <span class="menu-title text-truncate">{{ __('dashboard.roles') }}</span>
                    </a>
                </li>
            @endcan

            @can('admins')
                <li class="nav-item @yield('admins-active')">
                    <a class="d-flex align-items-center" href="{{ route('dashboard.admins.index') }}">
                        <i data-feather='users'></i>
                        <span class="menu-title text-truncate">{{ __('dashboard.admins') }}</span>
                        <span
                            class="badge badge-light-warning rounded-pill ms-auto me-1">{{ App\Models\Admin::count() }}</span>
                    </a>
                </li>
            @endcan

            {{-- @can('users')
                <li class="nav-item @yield('users-open') @yield('createUser-open')"><a class="d-flex align-items-center"
                        href="#"><i data-feather='users'></i><span class="menu-title text-truncate">
                            {{ __('dashboard.users') }}</span>
                        <span class="badge badge-light-warning rounded-pill ms-auto me-1"> {{ App\Models\User::count() }}
                        </span>
                    </a>
                    <ul class="menu-content">
                        <li><a class="@yield('users-active') d-flex align-items-center"
                                href="{{ route('dashboard.users.index') }}"><i data-feather="circle"></i><span
                                    class="menu-item text-truncate"
                                    data-i18n="Roles">{{ __('dashboard.users') }}</span></a>
                        </li>
                    </ul>
                </li>
            @endcan --}}

            {{-- @can('locations')
                <li class="nav-item @yield('locations-active')">
                    <a class="d-flex align-items-center" href="{{ route('dashboard.locations.index') }}">
                        <i data-feather='map'></i>
                        <span class="menu-title text-truncate">{{ __('dashboard.locations') }}</span>
                    </a>
                </li>
            @endcan
            @can('subscription-plans')
                <li class="nav-item @yield('subscription-plans-active')">
                    <a class="d-flex align-items-center" href="{{ route('dashboard.subscription-plans.index') }}">
                        <i data-feather="credit-card"></i>
                        <span class="menu-title text-truncate">{{ __('dashboard.subscription_plans') }}</span>
                    </a>
                </li>
            @endcan
            @can('properties')
                <li class="nav-item @yield('properties-active')">
                    <a class="d-flex align-items-center" href="{{ route('dashboard.properties.index') }}">
                        <i data-feather="map-pin"></i>
                        <span class="menu-title text-truncate">{{ __('dashboard.properties') }}</span>
                    </a>
                </li>
            @endcan
            @can('buildings')
                <li class="nav-item @yield('buildings-active')">
                    <a class="d-flex align-items-center" href="{{ route('dashboard.buildings.index') }}">
                        <i data-feather="layers"></i>
                        <span class="menu-title text-truncate">{{ __('dashboard.buildings') }}</span>
                    </a>
                </li>
            @endcan

            @can('property-roles')
                <li class="nav-item @yield('property-roles-active')">
                    <a class="d-flex align-items-center" href="{{ route('dashboard.property-roles.index') }}">
                        <i data-feather="user-check"></i>
                        <span class="menu-title text-truncate">{{ __('dashboard.property_roles') }}</span>
                        <span
                            class="badge badge-light-info rounded-pill ms-auto me-1">{{ App\Models\PropertyRoles::count() }}</span>
                    </a>
                </li>
            @endcan --}}

            @can('settings')
                <li class="nav-item @yield('settings-open')"><a class="d-flex align-items-center" href="#">
                        <i data-feather="settings"></i><span class="menu-title text-truncate"
                            data-i18n="Roles &amp; Permission">{{ __('dashboard.settings') }}</span>
                    </a>
                    <ul class="menu-content">
                        <li><a class="@yield('settings-active') d-flex align-items-center"
                                href="{{ route('dashboard.settings') }}"><i data-feather="circle"></i><span
                                    class="menu-item text-truncate"
                                    data-i18n="Roles">{{ __('dashboard.genral-setting') }}</span></a>
                        </li>
                    </ul>
                </li>
            @endcan

        </ul>
    </div>
</div>
