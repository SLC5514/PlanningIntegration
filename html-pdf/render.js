// var fs = require('fs');
// var pdf = require('html-pdf');
// var html = fs.readFileSync('./index.html', 'utf8');
// var options = {
//   format: 'A3',
//   orientation: 'landscape',
//   localUrlAccess: true,
//   childProcessOptions : {
//     detached : false
//   }
// };

// pdf.create(html, options).toFile('./test.pdf', function(err, res) {
//   if (err) return console.log(err);
//   console.log(res); // { filename: '/to/path/test.pdf' }
// });

// const puppeteer = require('puppeteer');

// (async () => {
//   const browser = await puppeteer.launch();
//   const page = await browser.newPage();
//   await page.goto('https://example.com');
//   await page.screenshot({ path: 'example.png' });

//   await browser.close();
// })();

const puppeteer = require("puppeteer");

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto(
    "https://www.pop-fashion.com/details/reportpdf/t_report-id_12188-col_133/?sign=202112300302620df959aac1876c791862dfafa27dafd&refresh=1",
    // "http://localhost:9000/",
    {
    waitUntil: "networkidle2",
    });
  await page.addStyleTag({
    content: '.report-pdf-hd{position: static;margin-bottom: -40px;}.report-pdf-hd .head-top-static{position: static;margin-bottom: -40px;}'
  })
  await page.pdf({
    path: "test.pdf",
    format: "a3",
    landscape: true,
    printBackground: true,
  });

  await browser.close();
})();
