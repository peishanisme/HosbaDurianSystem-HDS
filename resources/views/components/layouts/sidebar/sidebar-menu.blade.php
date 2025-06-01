<div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">

    <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
        data-kt-menu="true" data-kt-menu-expand="false">

        <x-layouts.sidebar.menu-item title="Dashboard" route="dashboard" />

        <div class="menu-item pt-5">
            <div class="menu-content">
                <span class="menu-heading fw-bold text-uppercase fs-7">Features</span>
            </div>
        </div>

        <x-layouts.sidebar.menu-accordion title="User Management" icon="ki-duotone ki-security-user"
            @class([
                'show active' => Route::is([
                    'user.users.index',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="Users Lising" route="user.users.index" />
        </x-layouts.sidebar.menu-accordion>

    </div>
</div>
