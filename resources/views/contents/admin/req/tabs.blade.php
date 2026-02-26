<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
    <li class="nav-item">
        <a class="nav-link {{ $pageData->activeMenu == 'req-bebas-pustaka' ? 'active' : '' }}"
            href="{{ route('app.req-bebas-pustaka.index') }}">Request Bebas Pustaka</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $pageData->activeMenu == 'kaperpus' ? 'active' : '' }}"
            href="{{ route('app.req-bebas-pustaka.show', ['param1' => 'kaperpus']) }}">Kelola Kaperpus</a>
    </li>
</ul>
