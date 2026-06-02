window.printQR = function (title) {
    const qrWrapper = document.getElementById('qrCodeWrapper');
    if (!qrWrapper) {
        alert('QR not found');
        return;
    }

    const qrContent = qrWrapper.innerHTML;
    title = title || 'QR Code';

    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);

    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 95vh;
                    font-family: sans-serif;
                }
                svg { width: 300px; height: 300px; }
                .title { margin-top: 16px; font-size: 20px; font-weight: bold; }
            </style>
        </head>
        <body>
            ${qrContent}
            <div class="title">${title}</div>
        </body>
        </html>
    `);
    doc.close();

    setTimeout(() => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    }, 300);
};

// window.printFruitLabels = function (treeUrl, treeTag, quantity) {
//     const iframe = document.createElement('iframe');

//     iframe.style.position = 'fixed';
//     iframe.style.right = '0';
//     iframe.style.bottom = '0';
//     iframe.style.width = '0';
//     iframe.style.height = '0';
//     iframe.style.border = '0';

//     document.body.appendChild(iframe);

//     const html = `
// <!DOCTYPE html>
// <html>
// <head>
//     <title>Print Fruits QR Labels</title>

//     <style>
//         @page {
//             size: A4;
//             margin: 10mm;
//         }

//         body {
//             margin: 0;
//             font-family: sans-serif;
//         }

//         .sheet {
//             display: grid;
//             grid-template-columns: repeat(7, 1fr);
//             gap: 0px;
//         }

//         .label {
//             border: 1px solid #000;
//             height: 130px;
//             display: flex;
//             flex-direction: column;
//             justify-content: center;
//             align-items: center;
//             page-break-inside: avoid;
//         }

//         .text {
//             font-size: 12px;
//             margin-top: 10px;
//             text-align: center;
//         }
//     </style>

//     <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
// </head>

// <body>
//     <div class="sheet">
//         ${Array.from({ length: quantity })
//             .map((_, i) => `
//                 <div class="label">
//                     <div class="qr" id="qr-${i}"></div>
//                     <div class="text">${treeTag}</div>
//                 </div>
//             `).join('')}
//     </div>

//     <script>
//         function generateQRs() {
//             return new Promise((resolve) => {
//                 for (let i = 0; i < ${quantity}; i++) {
//                     new QRCode(document.getElementById('qr-' + i), {
//                         text: "${treeUrl}",
//                         width: 70,
//                         height: 70
//                     });
//                 }

//                 setTimeout(resolve, 600);
//             });
//         }

//         window.onload = async function () {
//             await generateQRs();
//             window.print();
//         };

//         window.onafterprint = function () {
//             window.frameElement?.remove();
//         };
//     <\/script>
// </body>
// </html>
//     `;

//     iframe.srcdoc = html;
// };