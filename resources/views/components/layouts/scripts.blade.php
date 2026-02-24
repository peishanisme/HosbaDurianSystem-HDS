<!--begin::Javascript-->
<script>
    var hostUrl = "assets/";
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
{{-- sweetalert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.min.js"></script>

<script src="https://cdn.amcharts.com/lib/5/plugins/exporting.js"></script>

<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
<script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
<script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/create-app.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/new-target.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>

<script>
    function hideModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if (!modalElement) return;

        const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modalInstance.hide();

        setTimeout(() => {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style = '';
        }, 500);
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('alert-success', ({
            message,
            modalId
        }) => {
            console.log(message);

            if (modalId) {
                hideModal(modalId);
            }
            Swal.fire({
                icon: 'success',
                iconColor: '#17C653',
                titleText: 'Success',
                text: message,
                width: 400,
                padding: "0 0 2em",
                buttonsStyling: false,
                confirmButtonText: "Okay",
                customClass: {
                    confirmButton: "btn btn-success"
                }
            });
        });

        Livewire.on('alert-warning', ({
            message,
            modalId,
            action
        }) => {
            if (modalId) {
                hideModal(modalId);
            }
            Swal.fire({
                title: message,
                text: "You cannot revert this decision!",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: ['margin: 0 0.5em'],
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                customClass: {
                    confirmButton: "btn btn-warning",
                    cancelButton: "btn btn-secondary"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(action);
                }
            });
        });

        Livewire.on('alert-error', ({
            message,
            modalId
        }) => {
            if (modalId) {
                hideModal(modalId);
            }

            Swal.fire({
                icon: 'error',
                iconColor: '#F8285A',
                titleText: 'Oops!',
                text: message,
                width: 400,
                padding: "0 0 2em",
                buttonsStyling: false,
                confirmButtonText: "Retry",
                customClass: {
                    confirmButton: "btn btn-danger"
                }
            });
        });

        Livewire.on('alert-info', ({
            message,
            modalId
        }) => {
            if (modalId) {
                hideModal(modalId);
            }

            Swal.fire({
                icon: 'info',
                iconColor: '#7239EA',
                titleText: 'Info',
                text: message,
                width: 400,
                padding: "0 0 2em",
                buttonsStyling: false,
                confirmButtonText: "Okay",
                customClass: {
                    confirmButton: "btn btn-info"
                }
            });
        });

        Livewire.on('toast-success', ({
            message,
        }) => {
            console.log(message);

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            });
        });

        Livewire.on('toast-error', ({
            message,
        }) => {
            console.log(message);

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "error",
                title: message
            });
        });
    });
</script>

{{-- download qr script --}}
<script>
    function downloadQR(wrapperId, filename = 'qr-code.png') {
        console.log('Downloading QR Code...');
        const svg = document.querySelector(`#${wrapperId} svg`);
        if (!svg) {
            console.error('QR SVG not found');
            return;
        }

        const serializer = new XMLSerializer();
        const svgStr = serializer.serializeToString(svg);

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        const img = new Image();
        const blob = new Blob([svgStr], {
            type: 'image/svg+xml;charset=utf-8'
        });
        const url = URL.createObjectURL(blob);

        canvas.width = 300;
        canvas.height = 300;

        img.onload = function() {
            ctx.drawImage(img, 0, 0);
            URL.revokeObjectURL(url);

            const link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL('image/png');
            link.click();
        };

        img.src = url;
    }
</script>

{{-- qr scanner --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        let html5QrcodeScanner;

        function onScanSuccess(decodedText) {
            console.log('Raw scanned text:', decodedText);

            let uuid = null;

            try {
                const url = new URL(decodedText);
                const segments = url.pathname.split('/').filter(Boolean);
                uuid = segments[segments.length - 1];
            } catch (e) {
                // In case QR is not a valid URL
                uuid = decodedText;
            }

            // Optional UUID format validation
            const uuidRegex =
                /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;

            if (!uuidRegex.test(uuid)) {
                Livewire.dispatch('toast', {
                    type: 'warning',
                    message: 'Invalid QR code detected'
                });
                return;
            }

            console.log('Extracted UUID:', uuid);

            Livewire.dispatch('scan-fruit', {
                uuid
            });
        }

        let lastErrorAt = 0;

        function onScanFailure(error) {}

        Livewire.on('init-qr-scanner', () => {
            // Wait a bit to ensure #reader exists in DOM
            setTimeout(() => {
                const readerElem = document.getElementById("reader");
                if (!readerElem) {
                    console.error("QR Reader element not found.");
                    return;
                }

                // Clear old scanner if exists
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.clear().catch(err => console.warn(err));
                    html5QrcodeScanner = null;
                }

                html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                }, false);

                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            }, 300);
        });
    });
</script>

@livewireScripts
@stack('scripts')
