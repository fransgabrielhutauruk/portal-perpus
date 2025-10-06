<div id="kt_app_sidebar_wrapper" class="app-sidebar-wrapper hover-scroll-y my-5 my-lg-2" data-kt-scroll="true"
    data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
    data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper"
    data-kt-scroll-offset="5px">
    <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
        class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-6 mb-5">

        @if(auth()->user()->hasAnyRole(['user', 'admin']))
        <x-theme.menu link="{{ route('app.dashboard.index') }}" text="Dashboard" icon="ki-outline ki-graph-up" :active="$pageData->activeMenu == 'dashboard'" />
        <x-theme.menu text="Konten" icon="ki-outline ki-pin" :active="$pageData->activeRoot == 'konten'">
            <x-theme.submenu link="{{ route('app.post.index') }}" text="Post" :active="preg_match('/^post-kat-/', $pageData->activeMenu) ||
                $pageData->activeMenu == 'post-kategori' ||
                $pageData->activeMenu == 'post-label'" />
        </x-theme.menu>
        @endif

        @if(auth()->user()->hasAnyRole(['admin']))
        <div class="separator separator-dashed border-gray-10 my-2"></div>
        <x-theme.menu text="Manajemen Sistem" icon="ki-outline ki-setting-3" :active="$pageData->activeRoot == 'manajemen_sistem'">
            <x-theme.submenu link="{{ route('app.user.index') }}" text="Pengguna" :active="$pageData->activeMenu == 'pengguna'" />
        </x-theme.menu>
        @endif

    </div>
</div>