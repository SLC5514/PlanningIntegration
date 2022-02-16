var path = require('path');
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

try {

  const puppeteer = require("puppeteer");

  (async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    const timeout = 1000 * 60 * 10;
    const defParams = {
      path: "test.pdf",
      width: 1176,
      height: 868,
      margin: {
        left: 20,
        right: 20,
      },
      printBackground: true,
      timeout: 0,
      // format: "a3",
      // landscape: true,
    };
    const arguments = process.argv.splice(2);
    const params = {};
    const reg = /^--/;
    for (let i = 0; i < arguments.length; i++) {
      if (reg.test(arguments[i]) && !reg.test(arguments[i + 1])) {
        params[arguments[i].substring(2)] = isNaN(arguments[i + 1]) ? arguments[i + 1] : Number(arguments[i + 1]);
      }
    }
    for (const i in defParams) {
      if (params[i] === undefined) {
        params[i] = defParams[i]
      }
    }
    console.log(params)

    await page.setDefaultNavigationTimeout(timeout);
    await page.setDefaultTimeout(timeout);
    await page.goto(
      "https://www.pop-fashion.com/details/reportpdf/t_report-id_12642-col_21/?sign=2022020981510115e75018bb2d33aa3bad4bc6c9ebd52&refresh=1&generatepdf=1&zoom=1.5",
      {
        timeout: 0,
        // waitUntil: "networkidle2",
      });
    await page.addStyleTag({
      content: '.report-pdf-hd{position: static;margin-bottom: -40px;}.report-pdf-hd .head-top-static{position: static;margin-bottom: -40px;}.bless-alert{display: none;}'
    })
    await page.pdf({
      path: "test.pdf",
      // format: "a3",
      // landscape: true,
      width: 1176,
      height: 868,
      margin: {
        left: 20,
        right: 20,
      },
      printBackground: true,
      timeout: 0,
    });
    await browser.close();
  })();

} catch (err) {
  console.log('Error:', err);
}
