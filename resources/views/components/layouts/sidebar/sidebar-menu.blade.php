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
                    'tree.disease.index',
                    'tree.species.index',
                    'tree.trees.index',
                    'tree.trees.show',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="Species Listing" route="tree.species.index" />
            <x-layouts.sidebar.menu-sub-accordion title="Trees Listing" route="tree.trees.index" />
            <x-layouts.sidebar.menu-sub-accordion title="Diseases Listing" route="tree.disease.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- agrochemical --}}
        <x-layouts.sidebar.menu-accordion title="Agrochemicals" :svg="view('components.icons.agrochemical')" @class([
            'show active' => Route::is([
                'agrochemical.agrochemicals.index',
                'agrochemical.show',
                'agrochemical.purchase-history'
            ]),
        ])>
            <x-layouts.sidebar.menu-sub-accordion title="Agrochemicals Listing"
                route="agrochemical.agrochemicals.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- post-harvest --}}
        <x-layouts.sidebar.menu-accordion title="Post-Harvest" icon="ki-duotone ki-cube-2" @class([
            'show active' => Route::is([
                'harvest.events.index',
            ]),
        ])>
            <x-layouts.sidebar.menu-sub-accordion title="Harvest Events Listing"
                route="harvest.events.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- sales & transactions --}}
        <x-layouts.sidebar.menu-accordion title="Sales & Transactions" icon="ki-duotone ki-dollar"
            @class([
                'show active' => Route::is([
                    'sales.buyers.index',
                    'sales.buyers.show',
                    'sales.transaction.index',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="Buyers Listing" route="sales.buyers.index" />
            <x-layouts.sidebar.menu-sub-accordion title="Transactions Listing" route="sales.transaction.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- user management --}}
        <x-layouts.sidebar.menu-accordion title="User Management" icon="ki-duotone ki-security-user"
            @class([
                'show active' => Route::is([
                    'user.users.index',
                    'user.roles.index',
                    'user.permissions.index',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="Users Listing" route="user.users.index" />
            <x-layouts.sidebar.menu-sub-accordion title="Roles Listing" route="user.roles.index" />
            <x-layouts.sidebar.menu-sub-accordion title="Permissions Listing" route="user.permissions.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- activity log --}}
        <x-layouts.sidebar.menu-accordion title="Activity Log" icon="ki-duotone ki-bookmark-2"
            @class([
                'show active' => Route::is(['log.logs.index']),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="Logs" route="log.logs.index" />
        </x-layouts.sidebar.menu-accordion>

    </div>
</div>
