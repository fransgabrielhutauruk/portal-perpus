@php
    $tabsId = $tabsId ?? 'tabsContainer';
    $tabs = $tabs ?? [];
    $navFill = $navFill ?? true;
    $navClass = $navClass ?? '';
@endphp

<style>
    .nav-tabs .nav-link.active,
    .nav-tabs .nav-item.show .nav-link {
        color: var(--primary-main) !important;
        border-color: var(--bs-border-color) var(--bs-border-color) var(--primary-main) !important;
        border-bottom-width: 2px !important;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        border-color: var(--bs-border-color) var(--bs-border-color) var(--primary-main);
        color: var(--primary-main);
    }
    
    .nav-tabs .nav-link:not([disabled]):not(.active) {
        color: var(--primary-main);
    }
    
    .nav-tabs .nav-link:not([disabled]):not(.active):hover {
        color: var(--primary-main);
    }
    
    .nav-tabs .nav-link[disabled] {
        color: #6c757d !important;
        cursor: not-allowed !important;
        opacity: 0.5;
    }
</style>

<ul class="nav nav-tabs {{ $navFill ? 'nav-fill' : '' }} {{ $navClass }} px-5" id="{{ $tabsId }}" role="tablist">
    @foreach ($tabs as $index => $tab)
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link {{ $tab['active'] ?? false ? 'active' : '' }} fw-bold" 
                id="{{ $tab['id'] }}-btn"
                data-bs-toggle="tab"
                data-bs-target="#{{ $tab['id'] }}" 
                type="button" 
                role="tab"
                aria-controls="{{ $tab['id'] }}"
                aria-selected="{{ $tab['active'] ?? false ? 'true' : 'false' }}"
                {{ $index > 0 ? 'disabled' : '' }}>
                {{ $tab['label'] }}
            </button>
        </li>
    @endforeach
</ul>

<script>
    const visitedTabs = new Set(['{{ $tabs[0]['id'] ?? 'tab-attention' }}']); // First tab is always visited

    /**
     * Switch between tabs in a wizard-style interface
     * @param {string} targetId - The ID of the target tab (without #)
     */
    function switchTab(targetId) {
        const triggerEl = document.querySelector(`button[data-bs-target="#${targetId}"]`);
        if (!triggerEl) {
            console.error(`Tab with target #${targetId} not found`);
            return;
        }

        // Mark this tab as visited
        visitedTabs.add(targetId);

        // Enable this specific tab
        triggerEl.disabled = false;
        triggerEl.style.cursor = 'pointer';
        
        // Show the tab
        const tab = new bootstrap.Tab(triggerEl);
        tab.show();

        // Update all tabs: only visited tabs should be enabled
        document.querySelectorAll('.nav-link').forEach(btn => {
            const btnTarget = btn.getAttribute('data-bs-target')?.substring(1); // Remove # from target
            if (btnTarget && visitedTabs.has(btnTarget)) {
                btn.disabled = false;
                btn.style.cursor = 'pointer';
            } else {
                btn.disabled = true;
                btn.style.cursor = 'not-allowed';
            }
            btn.classList.remove('active');
        });

        triggerEl.classList.add('active');
    }
</script>
