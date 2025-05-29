<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <x-layouts.meta />
    <x-layouts.styles />
    <x-layouts.scripts />

    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<!--end::Head-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    class="app-default">
    <div class="flex grow">
        {{ $slot }}
    </div>

</body>

</html>
