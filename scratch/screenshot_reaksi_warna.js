const puppeteer = require('d:/xampp/htdocs/limsku/node_modules/puppeteer');
const fs = require('fs');

(async () => {
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    const page = await browser.newPage();
    const html = fs.readFileSync('d:/xampp/htdocs/limsku/scratch/test_render_reaksi_warna.html', 'utf8');
    await page.setContent(html, { waitUntil: 'networkidle0' });
    
    await page.setViewport({ width: 1200, height: 1600 });
    await page.screenshot({ path: 'd:/xampp/htdocs/limsku/scratch/test_render_reaksi_warna.png', fullPage: true });
    
    await page.pdf({
        path: 'd:/xampp/htdocs/limsku/scratch/test_render_reaksi_warna.pdf',
        format: 'A4',
        printBackground: true,
        margin: { top: 0, right: 0, bottom: 0, left: 0 }
    });
    
    await browser.close();
    console.log("SCREENSHOT & PDF GENERATED");
})();
