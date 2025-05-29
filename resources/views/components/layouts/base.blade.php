<x-layouts.app :title="$title">

    <x-layouts.sidebar.sidebar />

    <div class="wrapper flex grow flex-col">
        <x-layouts.header/>

       {{--  <main class="grow content pt-5 " id="content" role="content">
            <x-admin.toolbar :title="$title" />

            <div class="container-fixed">
                {{ $slot }}
            </div>
        </main>

        <x-admin.footer /> --}}
    </div>

</x-layouts.app>
