/*
 * @Author: SLC
 * @Date: 2021-07-22 16:21:07
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-22 18:11:30
 * @Description: file content
 */

const { app, BrowserWindow, Notification, Menu } = require("electron");
const fs = require("fs");
const path = require("path");

let progressInterval;

// 创建窗口
function createWindow() {
  const win = new BrowserWindow({
    width: 800,
    height: 600,
    webPreferences: {
      // 预加载从渲染器访问nodejs
      preload: path.join(__dirname, "preload.js"),
    },
  });
  win.loadFile("index.html");

  const INCREMENT = 0.03;
  const INTERVAL_DELAY = 100; // ms
  let c = 0;
  progressInterval = setInterval(() => {
    win.setProgressBar(c);
    if (c < 2) c += INCREMENT;
    else c = 0;
  }, INTERVAL_DELAY);
}

// Notification 主进程通知
const NOTIFICATION_TITLE = "Basic Notification";
const NOTIFICATION_BODY = "Notification from the Main process";

function showNotification() {
  new Notification({
    title: NOTIFICATION_TITLE,
    body: NOTIFICATION_BODY,
  }).show();
}

// 添加最近文件
const fileName = "recently-used.md";
fs.writeFile(fileName, "Lorem Ipsum", () => {
  app.addRecentDocument(path.join(__dirname, fileName));
});

// 菜单项
const template = [
  // 这里是菜单模版
  // {
  //   submenu: [
  //     {
  //       label: "Open Recent",
  //       role: "recentdocuments",
  //       submenu: [
  //         {
  //           label: "Clear Recent",
  //           role: "clearrecentdocuments",
  //         },
  //       ],
  //     },
  //   ],
  // },
];
const menu = Menu.buildFromTemplate(template);

// 生命周期
app
  .whenReady()
  .then(() => {
    Menu.setApplicationMenu(menu);

    createWindow();

    // 激活时没有窗口打开则打开一个窗口
    app.on("activate", function () {
      if (BrowserWindow.getAllWindows().length === 0) createWindow();
    });
  })
  .then(showNotification);

// 关闭所有窗口时退出应用
app.on("window-all-closed", function () {
  if (process.platform !== "darwin") app.quit();
});

// 退出前执行
app.on("before-quit", () => {
  clearInterval(progressInterval);
});
