<div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">

    <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
        data-kt-menu="true" data-kt-menu-expand="false">

        <x-layouts.sidebar.menu-item title="Dashboard" route="dashboard" />

        <div class="menu-item pt-5">
            <div class="menu-content">
                <span class="menu-heading fw-bold text-uppercase fs-7">Features</span>
            </div>
        </div>

        {{-- tree management --}}
        <x-layouts.sidebar.menu-accordion title="Tree Management" icon="ki-duotone ki-tree"
            @class([
                'show active' => Route::is([
                    'tree.species.index',
                    'tree.trees.index',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="Species Lising" route="tree.species.index" />
            <x-layouts.sidebar.menu-sub-accordion title="Trees Listing" route="tree.trees.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- user management --}}
        <x-layouts.sidebar.menu-accordion title="User Management" icon="ki-duotone ki-security-user"
            @class([
                'show active' => Route::is([
                    'user.users.index',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="Users Lising" route="user.users.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- activity log --}}
        <x-layouts.sidebar.menu-accordion title="Activity Log" icon="ki-duotone ki-bookmark-2"
            @class([
                'show active' => Route::is([
                    'log.logs.index',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="Logs" route="log.logs.index" />
        </x-layouts.sidebar.menu-accordion>

    </div>
</div>
