<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}"
    data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}"
    data-kt-sticky-animation="false">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
        id="kt_app_header_container">

        <!--begin::Header wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <!--begin::Menu wrapper-->
            <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
                data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
                data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
                data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
                data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                <!--begin::Menu-->
                <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
                    id="kt_app_header_menu" data-kt-menu="true">
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                        class="menu-item here show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
                        <!--begin:Menu link-->
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <span class="menu-title">{{ __('messages.dashboard') }}</span>
                            <span class="menu-arrow d-lg-none"></span>
                        </a>
                        <!--end:Menu link-->

                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Menu wrapper-->
            <!--begin::Navbar-->
            <div class="app-navbar flex-shrink-0">
                <!--begin::Language switcher-->
                <div class="app-navbar-item ms-1 ms-md-4">
                    <div class="cursor-pointer btn btn-light btn-sm"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-end">
                        {{ app()->getLocale() == 'ms' ? 'BM' : (app()->getLocale() == 'zh' ? 'CN' : strtoupper(app()->getLocale())) }}
                    </div>

                    <!--begin::Dropdown-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded fw-semibold py-4 fs-6 w-150px"
                        data-kt-menu="true">

                        <!-- English -->
                        <div class="menu-item px-3">
                            <a href="{{ route('lang.switch', ['locale' => 'en', 'redirect' => request()->fullUrl()]) }}"
                                class="menu-link px-3 {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                                data-kt-menu-dismiss="true">
                                English
                            </a>
                        </div>

                        <!-- Chinese -->
                        <div class="menu-item px-3">
                            <a href="{{ route('lang.switch', ['locale' => 'zh', 'redirect' => request()->fullUrl()]) }}"
                                class="menu-link px-3 {{ app()->getLocale() == 'zh' ? 'active' : '' }}"
                                data-kt-menu-dismiss="true">
                                中文 (Chinese)
                            </a>
                        </div>

                        <!-- Malay -->
                        <div class="menu-item px-3">
                            <a href="{{ route('lang.switch', ['locale' => 'ms', 'redirect' => request()->fullUrl()]) }}"
                                class="menu-link px-3 {{ app()->getLocale() == 'ms' ? 'active' : '' }}"
                                data-kt-menu-dismiss="true">
                                Bahasa Melayu
                            </a>
                        </div>

                    </div>

                    <!--end::Dropdown-->
                </div>
                <!--end::Language switcher-->

                <!--begin::User menu-->
                <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
                    <!--begin::Menu wrapper-->
                    <div class="cursor-pointer symbol symbol-35px"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">
                        <img src="{{ secure_asset('assets/media/placeholder/profile-icon.png') }}" class="rounded-3"
                            alt="user" />
                    </div>
                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50px me-5">
                                    <img src="{{ secure_asset('assets/media/placeholder/profile-icon.png') }}"
                                        class="rounded-3" alt="user" />
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Username-->
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->name }}
                                    </div>
                                    <span
                                        class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->phone }}</span>
                                </div>
                                <!--end::Username-->
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="{{ route('account.settings') }}" class="menu-link px-5">{{ __('messages.my_profile') }}</a>
                        </div>

                        <div class="separator my-2"></div>

                        <div class="menu-item px-5">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <span class="menu-link justify-center cursor-pointer"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('messages.logout') }}
                                </span>
                            </form>
                        </div>

                    </div>

                </div>

                <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
                    <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px"
                        id="kt_app_header_menu_toggle">
                        <i class="ki-duotone ki-element-4 fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
