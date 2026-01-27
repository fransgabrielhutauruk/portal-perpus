@php
    use App\Enums\UserRole;
@endphp

<div id="kt_app_sidebar_wrapper" class="app-sidebar-wrapper hover-scroll-y my-5 my-lg-2" data-kt-scroll="true"
    data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
    data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper"
    data-kt-scroll-offset="5px">
    <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
        class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-6 mb-5">

        @if (auth()->user()->hasAnyRole(UserRole::getAllRoles()))
            <x-theme.menu link="{{ route('app.dashboard.index') }}" text="Dashboard" icon="ki-outline ki-graph-up"
                :active="$pageData->activeMenu == 'dashboard'" />
            <x-theme.menu link="{{ route('app.berita.index') }}" text="Berita" icon="ki-outline ki-note-2"
                :active="$pageData->activeMenu == 'berita'" />
            <x-theme.menu link="{{ route('app.panduan.index') }}" text="Panduan" icon="ki-outline ki-book-square"
                :active="$pageData->activeMenu == 'panduan'" />
            <x-theme.menu link="{{ route('app.faq.index') }}" text="FAQ" icon="ki-outline ki-message-question"
                :active="$pageData->activeMenu == 'faq'" />
            <x-theme.menu link="{{ route('app.periode.index') }}" text="Periode" icon="ki-outline ki-abstract-45"
                :active="$pageData->activeMenu == 'periode'" />
            <x-theme.menu link="{{ route('app.usulan.index') }}" text="Req Buku" icon="ki-outline ki-book"
                :active="$pageData->activeMenu == 'usulan'" />
            <x-theme.menu link="{{ route('app.usulan-modul.index') }}" text="Req Modul" icon="ki-outline ki-book-open"
                :active="$pageData->activeMenu == 'usulan-modul'" />
            <x-theme.menu link="{{ route('app.req-bebas-pustaka.index') }}" text="Req Bebas Pustaka"
                icon="ki-outline ki-verify" :active="$pageData->activeMenu == 'req-bebas-pustaka'" />
            <x-theme.menu link="{{ route('app.req-turnitin.index') }}" text="Req Plagiarisme"
                icon="ki-outline ki-shield-tick" :active="$pageData->activeMenu == 'req-turnitin'" />
        @endif

        @if (auth()->user()->hasAnyRole([UserRole::ADMIN->value]))
            <div class="separator separator-dashed border-gray-10 my-2"></div>
            <x-theme.menu link="{{ route('app.pustakawan.index') }}" text="Pustakawan" icon="ki-outline ki-profile-user"
                :active="$pageData->activeMenu == 'pustakawan'" />
            <x-theme.menu link="{{ route('app.prodi.index') }}" text="Program Studi" icon="ki-outline ki-abstract-44"
                :active="$pageData->activeMenu == 'prodi'" />
            <x-theme.menu link="{{ route('app.user.index') }}" text="Pengguna" icon="ki-outline ki-setting-3"
                :active="$pageData->activeMenu == 'pengguna'" />
        @endif

    </div>
</div>
