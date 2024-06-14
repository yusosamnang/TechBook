<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Viewer</title>
    <link rel="stylesheet" href="https://mozilla.github.io/pdf.js/web/viewer.css">
    <style>
        body {
            margin-top: 3rem;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        #pdf-controls {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 800px;
            margin-bottom: 20px;
        }
        #pdf-controls button {
            padding: 10px 20px;
            font-size: 16px;
        }
        #pdf-viewer {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 1000px;
            overflow-y: auto;
            background: white;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        canvas {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div id="pdf-controls">
        <button id="prev-page">Previous Page</button>
        <button id="next-page">Next Page</button>
    </div>
    <div id="pdf-viewer"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pdfUrl = "{{ $pdfUrl }}";
            if (pdfUrl) {
                const pdfjsLib = window['pdfjs-dist/build/pdf'];
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';

                let pdfDoc = null,
                    pageNum = 1,
                    scale = 1.2;

                const container = document.getElementById('pdf-viewer');

                const renderPage = num => {
                    pdfDoc.getPage(num).then(page => {
                        const viewport = page.getViewport({ scale });

                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: context,
                            viewport
                        };
                        page.render(renderContext);

                        // Clear previous page
                        container.innerHTML = '';
                        container.appendChild(canvas);
                    });
                };

                const queueRenderPage = num => {
                    pageNum = num;
                    renderPage(pageNum);
                };

                document.getElementById('prev-page').addEventListener('click', () => {
                    if (pageNum <= 1) {
                        return;
                    }
                    queueRenderPage(pageNum - 1);
                });

                document.getElementById('next-page').addEventListener('click', () => {
                    if (pageNum >= pdfDoc.numPages) {
                        return;
                    }
                    queueRenderPage(pageNum + 1);
                });

                const loadingTask = pdfjsLib.getDocument(pdfUrl);
                loadingTask.promise.then(pdf => {
                    pdfDoc = pdf;
                    console.log('PDF loaded');
                    renderPage(pageNum);
                }, reason => {
                    console.error(reason);
                });
            } else {
                alert('No PDF URL provided!');
            }
        });
    </script>
</body>
</html>
