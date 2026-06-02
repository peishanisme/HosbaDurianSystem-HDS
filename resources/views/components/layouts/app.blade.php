<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Hosba Durian System</title>
    <x-layouts.meta />
    <x-layouts.styles />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    class="app-default">
    <!--begin::Theme mode setup on page load-->
    <!-- Page Loader -->


    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <!--end::Theme mode setup on page load-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            <!--begin::Header-->
            <x-layouts.header />
            <!--end::Header-->

            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                <!--begin::Sidebar-->
                <x-layouts.sidebar.sidebar />
                <!--end::Sidebar-->


                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">

                        <!--begin::Toolbar-->
                        <x-layouts.toolbar :title="$title" />
                        <!--end::Toolbar-->

                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid position-relative">

                            <!-- Loader ONLY for content -->
                            <div id="pageLoader"
                                style="position: absolute; inset: 0; background: #fff; z-index: 10; display: flex; align-items: center; justify-content: center;">
                                <div class="spinner-border text-primary"></div>
                            </div>

                            <!-- Actual Content -->
                            <div id="appContent" style="visibility: hidden;">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    {{ $slot }}
                                </div>
                            </div>

                        </div>
                        <!--end::Content-->

                    </div>
                    <!--end::Content wrapper-->

                    <!--begin::Footer-->
                    <x-layouts.footer />
                    <!--end::Footer-->

                </div>
            </div>
        </div>
    </div>

    <!--begin::Javascript-->
    <x-layouts.scripts />
    <script>
        window.addEventListener("load", function() {
            const loader = document.getElementById("pageLoader");
            const app = document.getElementById("appContent");

            if (app) {
                app.style.visibility = "visible";
                app.style.opacity = "1";
            }

            if (loader) {
                loader.style.opacity = "0";
                setTimeout(() => loader.style.display = "none", 200);
            }
        });

        // Livewire navigation
        document.addEventListener("livewire:navigating", () => {
            const loader = document.getElementById("pageLoader");
            if (loader) {
                loader.style.display = "flex";
                loader.style.opacity = "1";
            }
        });

        document.addEventListener("livewire:navigated", () => {
            const loader = document.getElementById("pageLoader");
            if (loader) {
                loader.style.opacity = "0";
                setTimeout(() => loader.style.display = "none", 200);
            }
        });
    </script>
    <!--end::Javascript-->
</body>

</html>
