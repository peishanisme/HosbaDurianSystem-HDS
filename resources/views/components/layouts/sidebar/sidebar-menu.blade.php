<div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">

    <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
        data-kt-menu="true" data-kt-menu-expand="false">

        {{-- Dashboard --}}
        <x-layouts.sidebar.menu-item title="{{ __('messages.dashboard') }}" route="dashboard" />

        <div class="menu-item pt-5">
            <div class="menu-content">
                <span class="menu-heading fw-bold text-uppercase fs-7">
                    {{ __('messages.features') ?? 'FEATURES' }}
                </span>
            </div>
        </div>

        {{-- Tree Management --}}
        <x-layouts.sidebar.menu-accordion title="{{ __('messages.tree_management') }}" icon="ki-duotone ki-tree"
            @class([
                'show active' => Route::is([
                    'tree.disease.index',
                    'tree.species.index',
                    'tree.trees.index',
                    'tree.show',
                    'tree.growth-log',
                    'tree.algochemical-usage',
                    'tree.harvest-record',
                    'tree.health-record',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.species_listing') }}"
                route="tree.species.index" />

            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.tree_listing') }}" route="tree.trees.index" />

            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.disease_listing') }}"
                route="tree.disease.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- Agrochemicals --}}
        <x-layouts.sidebar.menu-accordion title="{{ __('messages.agrochemicals') }}" :svg="view('components.icons.agrochemical')"
            @class([
                'show active' => Route::is([
                    'agrochemical.agrochemicals.index',
                    'agrochemical.agrochemicals.usage',
                    'agrochemical.show',
                    'agrochemical.purchase-history',
                    'agrochemical.application-record'
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.agrochemical_listing') }}"
                route="agrochemical.agrochemicals.index" />
            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.agrochemical_usage') }}"
                route="agrochemical.agrochemicals.usage" />
        </x-layouts.sidebar.menu-accordion>

        {{-- Post Harvest --}}
        <x-layouts.sidebar.menu-accordion title="{{ __('messages.post_harvest') }}" icon="ki-duotone ki-cube-2"
            @class([
                'show active' => Route::is(['harvest.events.index', 'harvest.show','harvest.harvest-summary']),
            ])>
            <x-layouts.sidebar.menu-sub-accordion
                title="{{ __('messages.harvest_events_listing') ?? 'Harvest Events Listing' }}"
                route="harvest.events.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- Sales & Transactions --}}
        <x-layouts.sidebar.menu-accordion title="{{ __('messages.sales_and_transactions') }}"
            icon="ki-duotone ki-dollar" @class([
                'show active' => Route::is([
                    'sales.buyers.index',
                    'sales.buyers.show',
                    'sales.transaction.index',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.buyer_listing') }}"
                route="sales.buyers.index" />

            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.transaction_listing') }}"
                route="sales.transaction.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- User Management --}}
        <x-layouts.sidebar.menu-accordion title="{{ __('messages.user_management') }}"
            icon="ki-duotone ki-security-user" @class([
                'show active' => Route::is([
                    'user.users.index',
                    'user.roles.index',
                    'user.permissions.index',
                ]),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.user_listing') }}" route="user.users.index" />

            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.role_listing') }}" route="user.roles.index" />

            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.permission_listing') }}"
                route="user.permissions.index" />
        </x-layouts.sidebar.menu-accordion>

        {{-- Activity Log --}}
        <x-layouts.sidebar.menu-accordion title="{{ __('messages.activity_logs') }}" icon="ki-duotone ki-bookmark-2"
            @class([
                'show active' => Route::is(['log.logs.index']),
            ])>
            <x-layouts.sidebar.menu-sub-accordion title="{{ __('messages.logs_listing') }}" route="log.logs.index" />
        </x-layouts.sidebar.menu-accordion>

    </div>
</div>
