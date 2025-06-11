    <a class="nav-link d-flex align-items-center text-nowrap text-sm fw-medium text-gray-700 @if (request()->routeIs('dashboard')) active fw-semibold text-dark @endif"
        href="{{ route('dashboard') }}">
        <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item here show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
            <span class="menu-link">
                <span class="menu-title">Dashboards</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
        </div>
    </a>
