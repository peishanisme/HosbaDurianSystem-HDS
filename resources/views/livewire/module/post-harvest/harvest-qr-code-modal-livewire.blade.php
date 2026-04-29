<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="d-flex justify-content-center">
        <div class="text-center">
            @if (!$tree)
                <div class="spinner-border mb-4" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            @else
                <div class="qr-code-container mb-5">
                    {!! QrCode::size(150)->generate(route('public.portal', $tree->uuid)) !!}
                </div>

                <div class="fs-4 fw-bold mb-2">{{ $tree->tree_tag }}</div>

                <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-4">

                        {{-- <!-- Decrease -->
                        <button wire:click="decrement" class="btn btn-light">
                            <i class="ki-duotone ki-minus fs-2"></i>
                        </button> --}}

                        <!-- Input -->
                        <input type="number" wire:model.debounce.300ms="quantity" min="1"
                            class="form-control text-center" style="width: 70px;" />

                        {{-- <!-- Increase -->
                        <button wire:click="increment" class="btn btn-light">
                            <i class="ki-duotone ki-plus fs-2"></i>
                        </button> --}}

                    </div>
                </div>
            @endif
        </div>

        @slot('footer')
            <div class="wire:ignore">
                <button type="button" class="btn btn-primary" wire:click='printQr'>
                    Print QR Codes
                </button>
            </div>

            <x-button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        @endslot
</x-modal-component>

@push('scripts')
    <script>
        window.addEventListener('print-fruit-labels', (event) => {
            const {
                treeUrl,
                treeTag,
                quantity
            } = event.detail;

            window.printFruitLabels(treeUrl, treeTag, quantity);
        });
    </script>
    
    <script>
        window.printFruitLabels = function(treeUrl, treeTag, quantity) {
                const iframe = document.createElement('iframe');

                iframe.style.position = 'fixed';
                iframe.style.right = '0';
                iframe.style.bottom = '0';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = '0';

                document.body.appendChild(iframe);

                const html = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print Fruits QR Labels</title>

            <style>
                @page {
                    size: A4;
                    margin: 10mm;
                }

                body {
                    margin: 0;
                    font-family: sans-serif;
                }

                .sheet {
                    display: grid;
                    grid-template-columns: repeat(7, 1fr);
                    gap: 0px;
                }

                .label {
                    border: 1px solid #000;
                    height: 130px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    page-break-inside: avoid;
                }

                .text {
                    font-size: 12px;
                    margin-top: 10px;
                    text-align: center;
                }
            </style>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js">
    </script>
    </head>

    <body>
        <div class="sheet">
            ${Array.from({ length: quantity })
            .map((_, i) => `
            <div class="label">
                <div class="qr" id="qr-${i}"></div>
                <div class="text">${treeTag}</div>
            </div>
            `).join('')}
        </div>

        <script>
            function generateQRs() {
                return new Promise((resolve) => {
                    for (let i = 0; i < $ {
                            quantity
                        }; i++) {
                        new QRCode(document.getElementById('qr-' + i), {
                            text: "${treeUrl}",
                            width: 70,
                            height: 70
                        });
                    }

                    setTimeout(resolve, 600);
                });
            }

            window.onload = async function() {
                await generateQRs();
                window.print();
            };

            window.onafterprint = function() {
                window.frameElement?.remove();
            }; < \/script> < /
            body > <
                /html>
            `;

                    iframe.srcdoc = html;
                };
        </script>
    @endpush
