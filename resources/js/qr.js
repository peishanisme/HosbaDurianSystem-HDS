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
