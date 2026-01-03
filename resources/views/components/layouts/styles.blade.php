 <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('assets/favicon/apple-touch-icon.png') }}">
 <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/favicon/favicon-32x32.png') }}">
 <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/favicon/favicon-16x16.png') }}">
 <link rel="shortcut icon" href="{{ secure_asset('assets/favicon/favicon.ico') }}">
 <link rel="manifest" href="{{ secure_asset('assets/favicon/site.webmanifest') }}">
 <link rel="mask-icon" href="{{ secure_asset(path: 'assets/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">

 {{-- <link rel="canonical" href="http://preview.keenthemes.comindex.html" />
 <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" /> --}}
 <!--begin::Fonts(mandatory for all pages)-->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
 <!--end::Fonts-->
 <!--begin::Vendor Stylesheets(used for this page only)-->
 <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
     type="text/css" />
 <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
 <!--end::Vendor Stylesheets-->
 <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
 <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" />
 {{-- <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" /> --}}
 <!--end::Global Stylesheets Bundle-->
 {{-- sweetalert2 --}}
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

 @stack('styles')
