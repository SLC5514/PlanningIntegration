try {
  // const puppeteer = require("puppeteer");
  const { chromium } = require('playwright');  // Or 'chromium' or 'firefox'.

  (async () => {
    const timeout = 1000 * 60 * 10;
    // const browser = await puppeteer.launch({
    //     timeout: timeout,
    //     args: ['--no-sandbox', '--disable-setuid-sandbox']
    // });
    // const page = await browser.newPage();
    const browser = await chromium.launch();
    const context = await browser.newContext();
    const page = await context.newPage();

    // 参数配置 --site 站点（1-5） --url 页面链接 --path 保存位置
    const defParams = {
      path: "test.pdf",
      width: 1176,
      height: 868,
      left: 20,
      right: 20,
      printBackground: true,
      timeout: timeout,
      // format: "a3",
      // landscape: true,
      // preferCSSPageSize: true,
    };
    const siteParams = {
      1: {
        width: 1176,
        height: 868,
      },
      2: {
        width: 1176,
        height: 868,
      },
      3: {
        width: 1176,
        height: 868,
      },
      4: {
        width: 1176,
        height: 868,
      },
      5: {
        width: 1176,
        height: 868,
      },
    }

    // 参数归整
    const arguments = process.argv.splice(2);
    const nodeParams = {}; // --url 页面链接
    const params = {}; // 最终参数
    const reg = /^--/;
    for (let i = 0; i < arguments.length; i++) {
      if (reg.test(arguments[i]) && !reg.test(arguments[i + 1])) {
        nodeParams[arguments[i].substring(2)] = isNaN(arguments[i + 1]) ? arguments[i + 1] : Number(arguments[i + 1]);
      }
    }
    Object.assign(params, defParams, siteParams[nodeParams.site] || {}, nodeParams);
    // console.log(params)

    // 必传项 --url 页面链接 --path 保存位置/xxx/xxx.pdf
    if (!params.url) {
      console.log('Error:', '请传入页面链接 --url');
      process.exit(1);
    }
    if (!params.path) {
      console.log('Error:', '请传入保存位置 --path');
      process.exit(1);
    }

    // 执行
    // await page.setDefaultNavigationTimeout(params.timeout);
    // await page.setDefaultTimeout(params.timeout);
    // await page.goto(params.url, { waitUntil: 'networkidle0' });
    // 解决头部绝对定位遮挡
    // await page.addStyleTag({
    //     content: '.report-pdf-hd{position: relative;margin-bottom: -40px;}.report-pdf-hd .head-top-static{position: relative;margin-bottom: -40px;}'
    // })
    // await page.pdf({
    //     path: params.path,
    //     width: params.width,
    //     height: params.height,
    //     scale: 0.5,
    //     margin: {
    //         left: params.left,
    //         right: params.right,
    //     },
    //     printBackground: params.printBackground,
    //     timeout: params.timeout
    // });
    // await browser.close();

    await page.goto(params.url);
    await page.pdf({
      path: params.path,
      width: params.width,
      height: params.height,
      scale: 0.5,
      margin: {
        left: params.left,
        right: params.right,
      },
      printBackground: params.printBackground,
      timeout: params.timeout
    });
    await browser.close();
  })();
} catch (err) {
  console.log('Error:', err);
  process.exit(1);
}

// node render --site 1 --url "http://localhost:9000/" --path test.pdf





// const { webkit } = require('playwright');  // Or 'chromium' or 'firefox'.

// (async () => {
//   const browser = await webkit.launch();
//   const context = await browser.newContext();
//   const page = await context.newPage();
//   await page.goto('https://example.com');
//   await page.screenshot({path: 'screenshot.png'});
//   await browser.close();
// })();
