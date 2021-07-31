/*
 * @Author: SLC
 * @Date: 2021-07-27 16:21:07
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-31 16:48:34
 * @Description: file content
 */

const { app, BrowserWindow, Menu/* , globalShortcut, Notification */ } = require("electron");
// const fs = require("fs");
const path = require("path");

// let progressInterval;

// 创建窗口
function createWindow() {
  const win = new BrowserWindow({
    width: 300,
    height: 400,
    webPreferences: {
      nodeIntegration: true,
      contextIsolation: false,
      // 预加载从渲染器访问nodejs
      preload: path.join(__dirname, "./preload/index.js"),
    },
  });
  win.loadFile("index.html");

  // 进度
  // const INCREMENT = 0.03;
  // const INTERVAL_DELAY = 100; // ms
  // let c = 0;
  // progressInterval = setInterval(() => {
  //   win.setProgressBar(c);
  //   if (c < 2) c += INCREMENT;
  //   else c = 0;
  // }, INTERVAL_DELAY);
}

// Notification 主进程通知
// const NOTIFICATION_TITLE = "Basic Notification";
// const NOTIFICATION_BODY = "Notification from the Main process";
// function showNotification() {
//   new Notification({
//     title: NOTIFICATION_TITLE,
//     body: NOTIFICATION_BODY,
//   }).show();
// }

// 添加最近文件
// const fileName = "recently-used.md";
// fs.writeFile(fileName, "Lorem Ipsum", () => {
//   app.addRecentDocument(path.join(__dirname, fileName));
// });

// 菜单项
// const template = [
//   // 这里是菜单模版
//   {
//     submenu: [
//       {
//         label: "Open Recent",
//         role: "recentdocuments",
//         submenu: [
//           {
//             label: "Clear Recent",
//             role: "clearrecentdocuments",
//           },
//         ],
//       },
//     ],
//   },
// ];
// const menu = Menu.buildFromTemplate(template);
const template = [
  {
    label: 'DevTools',
    submenu: [{
      role: 'toggleDevTools',
      accelerator: 'CommandOrControl+Shift+I'
    }]
  }
]
const menu = Menu.buildFromTemplate(template);

/* 生命周期 ========================================================================= */
app
  .whenReady()
  // .then(() => {
  //   globalShortcut.register('Alt+CommandOrControl+I', () => {
  //     win.webContents.openDevTools();
  //   })
  // })
  .then(() => {
    Menu.setApplicationMenu(menu);

    createWindow();

    // 激活时没有窗口打开则打开一个窗口
    app.on("activate", function () {
      if (BrowserWindow.getAllWindows().length === 0) createWindow();
    });
  })
  // .then(showNotification);

// 关闭所有窗口时退出应用
app.on("window-all-closed", function () {
  if (process.platform !== "darwin") app.quit();
});

// 退出前执行
// app.on("before-quit", () => {
//   clearInterval(progressInterval);
// });

// app.on("will-quit", () => {
//   // 注销快捷键
//   globalShortcut.unregister('CommandOrControl+X')
//   // 注销所有快捷键
//   globalShortcut.unregisterAll()
// });

// require('devtron').install();
