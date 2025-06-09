<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
    <!--begin::Logo image-->
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center py-5">
        <img alt="Logo" src="{{ secure_asset('assets/media/logos/system-logo.png') }}" class="app-sidebar-logo-default mt-5" style="height: 170px;" />
    </a>

    <div id="kt_app_sidebar_toggle"
        class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
        data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
        data-kt-toggle-name="app-sidebar-minimize">
        <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>
    <!--end::Sidebar toggle-->
</div>
