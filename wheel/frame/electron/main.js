/*
 * @Author: SLC
 * @Date: 2021-07-22 16:21:07
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-22 16:40:42
 * @Description: file content
 */

const path = require('path');
const { app, BrowserWindow } = require("electron");

function createWindow() {
  const win = new BrowserWindow({
    width: 800,
    height: 600,
    webPreferences: { // 预加载从渲染器访问nodejs
      preload: path.join(__dirname, "preload.js"),
    },
  });
  win.loadFile("index.html");
}

app.whenReady().then(() => {
  createWindow();

  // 没有窗口打开则打开一个窗口 (macOS 激活时)
  app.on("activate", function () {
    if (BrowserWindow.getAllWindows().length === 0) createWindow();
  });
});

// 关闭所有窗口时退出应用 (Windows & Linux)
app.on("window-all-closed", function () {
  if (process.platform !== "darwin") app.quit();
});
