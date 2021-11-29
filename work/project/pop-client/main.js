/*
 * @Author: SLC
 * @Date: 2021-07-27 16:21:07
 * @LastEditors: SLC
 * @LastEditTime: 2021-11-09 16:17:18
 * @Description: file content
 */

const { app, BrowserWindow, Menu, ipcMain, dialog, session/* , globalShortcut, Notification */ } = require("electron");
// const fs = require("fs");
const path = require("path");
const { version } = require("./package.json");
// const cp = require("child_process");

// let progressInterval;
let win;

// 创建窗口
function createWindow() {
  win = new BrowserWindow({
    title: "POP客户端 V" + version.split('.').slice(0, 2).join('.'),
    width: 300,
    height: 410,
    icon: path.join(__dirname, "./favicon.ico"),
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
  // {
  //   label: 'DevTools',
  //   submenu: [{
  //     role: 'toggleDevTools',
  //     accelerator: 'CommandOrControl+Shift+I'
  //   }]
  // }
]
const menu = Menu.buildFromTemplate(template);

/* 生命周期 ========================================================================= */

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
  // .then(showNotification);

// 关闭所有窗口时退出应用
app.on("window-all-closed", function () {
  if (process.platform !== "darwin") app.quit();
});

/* 进程通信 ========================================================================= */

// 退出
ipcMain.on("quit", () => {
  win.close();
});

// 调整尺寸
ipcMain.on("setBounds", (event, data) => {
  win.setBounds({
    width: data.width,
    height: data.height,
  })
});

// 消息弹框
ipcMain.on("showMessageBox", (event, data) => {
  dialog.showMessageBox({
    type: data.type,
    title: data.title,
    message: data.message,
    buttons: data.buttons
  }).catch((error) => {
    console.error(error)
  })
});

// 数据存储
ipcMain.handle('sessionCookies', async (event, data) => {
  if (data.type === 'get') {
    return await getCookies(data.url, data.name);
  } else if (data.type === 'set') {
    return await setCookie(data.url, data.name, data.value, data.days);
  } else if (data.type === 'remove') {
    return await removeCookies(data.url, data.name);
  }
})

/* 数据存储 ========================================================================= */

// 获得
async function getCookies(url, name) {
  let cookies = [];
  try {
    cookies = await session.defaultSession.cookies.get({ url: url, name: name });
  } catch (error) {
    console.error(error);
  }
  return cookies;
};

// 保存
async function setCookie(url, name, value, days) {
  let status = false;
  let exp = new Date();
  let date = days ? Math.round(exp.getTime() / 1000) + days * 24 * 60 * 60 : '';
  const cookie = {
    url: url,
    name: name,
    value: value,
    expirationDate: date
  };
  try {
    await session.defaultSession.cookies.set(cookie);
    status = true;
  } catch (error) {
    console.error(error);
  }
  return status;
};

// 删除
async function removeCookies(url, name) {
  let status = false;
  try {
    await session.defaultSession.cookies.remove(url, name);
    status = true;
  } catch (error) {
    console.error(error);
  }
  return status;
};
