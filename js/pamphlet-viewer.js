import { getDocument, GlobalWorkerOptions } from '../vendor/pdfjs/build/pdf.mjs';

GlobalWorkerOptions.workerSrc = new URL('../vendor/pdfjs/build/pdf.worker.mjs', import.meta.url).href;
const viewer = document.querySelector('.pamphlet-pdf-viewer');
const canvas = document.getElementById('pdf-canvas');
const container = document.querySelector('.pdf-canvas-container');
const status = document.getElementById('pdf-status');
const previous = document.getElementById('pdf-prev');
const next = document.getElementById('pdf-next');
const pageSelect = document.getElementById('pdf-page');
const zoom = document.getElementById('pdf-zoom');
let pdf;
let pageNumber = 1;
let rendering = false;
let pending = false;

async function renderPage() {
    if (!pdf) return;
    if (rendering) { pending = true; return; }
    rendering = true;
    const current = pageNumber;
    status.textContent = 'ページを表示しています…';
    try {
        const page = await pdf.getPage(current);
        const base = page.getViewport({ scale: 1 });
        const scale = zoom.value === 'fit' ? Math.max(1, container.clientWidth - 2) / base.width : Number(zoom.value);
        const viewport = page.getViewport({ scale });
        const ratio = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = Math.floor(viewport.width * ratio);
        canvas.height = Math.floor(viewport.height * ratio);
        canvas.style.width = `${viewport.width}px`;
        canvas.style.height = `${viewport.height}px`;
        await page.render({ canvasContext: canvas.getContext('2d'), viewport, transform: [ratio, 0, 0, ratio, 0, 0] }).promise;
        canvas.setAttribute('aria-label', `パンフレット ${current} / ${pdf.numPages} ページ`);
        status.textContent = `${current} / ${pdf.numPages} ページ`;
    } catch (error) {
        console.error(error);
        status.textContent = '表示できませんでした。「PDFを開く」または下のページ画像をご利用ください。';
    } finally {
        rendering = false;
        previous.disabled = pageNumber <= 1;
        next.disabled = pageNumber >= pdf.numPages;
        pageSelect.value = String(pageNumber);
        if (pending) { pending = false; renderPage(); }
    }
}

previous.addEventListener('click', () => { pageNumber = Math.max(1, pageNumber - 1); renderPage(); });
next.addEventListener('click', () => { pageNumber = Math.min(pdf.numPages, pageNumber + 1); renderPage(); });
pageSelect.addEventListener('change', () => { pageNumber = Number(pageSelect.value); renderPage(); });
zoom.addEventListener('change', renderPage);
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => { if (zoom.value === 'fit') renderPage(); }, 150);
});

try {
    pdf = await getDocument({
        url: viewer.dataset.pdfUrl,
        cMapUrl: new URL('../vendor/pdfjs/cmaps/', import.meta.url).href,
        cMapPacked: true,
        standardFontDataUrl: new URL('../vendor/pdfjs/standard_fonts/', import.meta.url).href,
        wasmUrl: new URL('../vendor/pdfjs/wasm/', import.meta.url).href,
        isEvalSupported: false,
    }).promise;
    for (let i = 1; i <= pdf.numPages; i++) pageSelect.add(new Option(String(i), String(i)));
    document.getElementById('pdf-count').textContent = `/ ${pdf.numPages}`;
    pageSelect.disabled = false;
    zoom.disabled = false;
    await renderPage();
} catch (error) {
    console.error(error);
    status.textContent = 'PDFを読み込めませんでした。「PDFを開く」または下のページ画像をご利用ください。';
}
