/*
 * @Author: SLC
 * @Date: 2021-11-10 18:14:57
 * @LastEditors: SLC
 * @LastEditTime: 2021-11-10 18:16:06
 * @Description: file content
 */

// const package = require("../package.json");
// const { contextBridge, shell } = require("electron");
const cp = require("child_process");

// 自定义api
// contextBridge.exposeInMainWorld("electron", {
//   // openUrl: (url) => shell.openExternal(url),
//   // getDefBrowser: getDefBrowser,
//   // getBrowserList: getBrowserList,
//   // mac: require("getmac").default(),
//   cpExec: (cmd, cwd, url) =>
//     cp.exec("start " + cmd + " " + url, { cwd }),
// });

window.electron = {
  // openUrl: (url) => shell.openExternal(url),
  getDefBrowser: getDefBrowser,
  // getBrowserList: getBrowserList,
  // mac: require("getmac").default(),
  cpExec: (cmd, cwd, url, callback) =>
    cp.exec('start ' + (cmd || '""') + ' "' + url + '"', { cwd }, function(error, stdout, stderr) {
      callback && callback(error, stdout, stderr);
    }),
};

// 监听dom加载完成
// window.addEventListener("DOMContentLoaded", () => {
//   const replaceText = (selector, text) => {
//     const element = document.getElementById(selector);
//     if (element) element.innerText = text;
//   };
//   for (const dependency of ["chrome", "node", "electron"]) {
//     replaceText(`${dependency}-version`, process.versions[dependency]);
//   }
// });

// 获取浏览器列表
// const stdoutStr = iconv.decode(new Buffer.from(stdout, binaryEncoding), encoding);
// , { encoding: binaryEncoding }

// const iconv = require("iconv-lite");
// const encoding = "cp936";
// const binaryEncoding = "binary";

const cmds = [
  "REG QUERY HKEY_CURRENT_USER\\SOFTWARE\\Clients\\StartMenuInternet",
  "REG QUERY HKEY_LOCAL_MACHINE\\SOFTWARE\\Clients\\StartMenuInternet",
  "REG QUERY HKEY_LOCAL_MACHINE\\SOFTWARE\\WOW6432Node\\Clients\\StartMenuInternet",
  "REG QUERY HKEY_USERS\\S-1-5-21-2288573961-3189969137-260067321-1005_Classes\\Software\\Tencent",
];
const obj = {
  "360se.exe": "360浏览器",
  "360chrome.exe": "360极速浏览器",
  "chrome.exe": "谷歌浏览器",
  "iexplore.exe": "IE浏览器",
  "msedge.exe": "Edge浏览器",
  "SogouExplorer.exe": "搜狗浏览器",
  "firefox.exe": "火狐浏览器",
  "QQBrowser.exe": "QQ浏览器",
};
let browserList = [];

function getDefBrowser(callback) {
  const defBrCmd =
    "REG QUERY HKEY_CURRENT_USER\\Software\\Microsoft\\Windows\\Shell\\Associations\\UrlAssociations\\http\\UserChoice /v ProgId";
  cp.exec(defBrCmd, (error, stdout, stderr) => {
    if (error) {
      console.log("error:", error);
      return;
    }
    if (stdout) {
      const defKey = stdout.trim().split(/\s+/)[3];
      cp.exec(
        "REG QUERY HKEY_CLASSES_ROOT\\" + defKey + "\\Shell\\open\\command",
        (error, stdout, stderr) => {
          if (error) {
            console.log("error:", error);
            return;
          }
          let defBrowser = null;
          if (stdout) {
            let cmd = stdout
              .trim()
              .split(/\r\n/)[1]
              .trim()
              .split("    ")[2]
              .replace(/\"/g, "")
              .replace(/(.*\.exe).*/g, "$1");
            const cwd = cmd.replace(/(.*)\\([^\\]+)/g, "$1");
            cmd = cmd.replace(/(.*)\\([^\\]+)/g, "$2");
            const name = getBrowserName(cmd);
            defBrowser = {
              name: name,
              cmd: cmd,
              cwd: cwd,
            };
          }
          callback(defBrowser);
        }
      );
    }
  });
}
function getBrowserList(callback) {
  if (browserList.length) {
    callback(browserList);
    return;
  }
  const cmdstr = cmds.join("&&");
  cp.exec(cmdstr, (error, stdout, stderr) => {
    if (error) {
      console.log("error:", error);
      return;
    }
    if (stdout) {
      // console.log(
      //   iconv.decode(new Buffer.from(stdout, binaryEncoding), encoding)
      // );
      const apps = [],
        arr = stdout.trim().split("\r\n");
      for (let i in arr) {
        if (
          arr[i] &&
          (arr[i].indexOf("StartMenuInternet\\") > -1 ||
            arr[i].indexOf("Tencent\\") > -1)
        ) {
          apps.push(arr[i]);
        }
      }
      // console.log(apps);
      const len = apps.length;
      let temp = {},
        count = 0;
      for (let i in apps) {
        cp.exec(
          'REG QUERY "' + apps[i] + '\\shell\\open\\command"',
          (error, stdout, stderr) => {
            if (error) {
              count++;
              console.log("获取应用信息错误");
              return;
            }
            const items = stdout.trim().split("\r\n");
            let cmd = items[1].trim().split("    ");
            cmd = cmd[2].replace(/\"/g, "").replace(/(.*\.exe).*/g, "$1");
            const cwd = cmd.replace(/(.*)\\([^\\]+)/g, "$1");
            cmd = cmd.replace(/(.*)\\([^\\]+)/g, "$2");
            if (!temp[cmd]) {
              temp[cmd] = cwd;
              let name = getBrowserName(cmd);
              browserList.push({
                name: name,
                cmd: cmd,
                cwd: cwd,
              });
              // $('#internet').append(`<option data-cmd="${cmd}" data-cwd="${cwd}">${name}</option>`);
            }
            count++;
            if (len === count) {
              callback(browserList.sort((a, b) => a.name - b.name));
            }
          }
        );
      }
    }
  });
}
function getBrowserName(cmd) {
  if (obj[cmd]) {
    return obj[cmd];
  } else {
    return cmd;
  }
}

// console.log(process.cwd());
// cp.exec(
//   "start QQBrowser.exe http://www.baidu.com",
//   { cwd: "D:\\Program Files (x86)\\Tencent\\QQBrowser" },
//   (error, stdout, stderr) => {
//     if (error) {
//       console.log("error:", error);
//       return;
//     }
//     if (stdout) {
//       console.log(stdout);
//     }
//   }
// );
