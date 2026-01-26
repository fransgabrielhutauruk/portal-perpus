@props(['menu' => $menu ?? [], 'jurusanList' => $jurusanList ?? []])
@php
    // dd(data_get($siteIdentity, 'identity.logo_path'));
@endphp

<header class="main-header">
    <div class="header-sticky">
        <nav class="navbar navbar-expand-xxl" {{ Route::currentRouteName() == 'frontend.home' ? 'data-is-home' : '' }}>
            <div class="container-fluid">
                <a class="navbar-brand mt-md-2" href="{{ route('frontend.home') }}">
                    <img src="{{ data_get($siteIdentity, 'identity.logo_path') }}" alt="Logo" class="navbar-logo">
                </a>

                <div class="collapse navbar-collapse main-menu">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            <li class="nav-item toggle-menu">
                                <div class="navbar-brand">
                                    <img src="{{ data_get($siteIdentity, 'identity.logo_path') }}" alt="Logo"
                                        class="navbar-logo">
                                </div>

                                <div class="navbar-toggle navbar-toggle-responsive"></div>
                            </li>
                            @foreach ($menu as $menu_item)
                                @php
                                    $class = 'nav-item';
                                    $route = $menu_item['route'] ?? 'javascript:;';

                                    if ($menu_item['children'] ?? false) {
                                        $class .= ' submenu';
                                    }

                                    if ($menu_item['hide_xxl'] ?? false) {
                                        $class .= ' hide-xxl';
                                    }
                                @endphp
                                <li class="{{ $class }}">
                                    <a class="nav-link" href="{{ $route }}"
                                        @if (isset($menu_item['target'])) target="{{ $menu_item['target'] }}" @endif>
                                        {{ $menu_item['name'] }}
                                    </a>
                                    @if (isset($menu_item['children']))
                                        <ul>
                                            @foreach ($menu_item['children'] as $child)
                                                @php
                                                    $child_class = 'nav-item';
                                                    $child_route = $child['route'] ?? 'javascript:;';

                                                    if (isset($child['children'])) {
                                                        $child_class .= ' submenu';
                                                    }
                                                @endphp
                                                <li class="{{ $child_class }}">
                                                    <a class="nav-link" href="{{ $child_route }}"
                                                        @if (isset($child['target'])) target="{{ $child['target'] }}" @endif>
                                                        {{ $child['name'] }}
                                                    </a>
                                                    @if (isset($child['children']))
                                                        <ul>
                                                            @foreach ($child['children'] as $sub_child)
                                                                @php
                                                                    $sub_child_route =
                                                                        $sub_child['route'] ?? 'javascript:;';
                                                                @endphp

                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="{{ $sub_child_route }}"
                                                                        @if (isset($sub_child['target'])) target="{{ $sub_child['target'] }}" @endif>
                                                                        {{ $sub_child['name'] }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                            <li class="nav-item d-md-none">
                                @auth
                                    <a class="nav-link" href="{{ route('app.dashboard.index') }}">
                                        Dashboard
                                    </a>
                                @else
                                    <a class="nav-link" href="{{ route('login.google', ['provider' => 'google']) }}">
                                        Login
                                    </a>
                                @endauth
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="header-btn-- d-inline-flex d-none d-md-block mt-md-2">
                    @auth
                        <a href="{{ route('app.dashboard.index') }}"
                            class="btn-default btn-highlighted text-nowrap contact-button-">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login.google', ['provider' => 'google']) }}"
                            class="btn-default btn-highlighted text-nowrap contact-button-">
                            Login
                        </a>
                    @endauth
                </div>
                <div class="navbar-toggle"></div>
            </div>
        </nav>
        <div class="responsive-menu slicknav_state"></div>
    </div>
</header>
