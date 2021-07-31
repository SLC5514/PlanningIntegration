/*
 * @Author: SLC
 * @Date: 2021-07-27 11:27:46
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-31 17:59:30
 * @Description: file content
 */

// const fs = require('fs')
// console.log(fs.readFileSync(__filename, 'utf8'))

// const { shell } = require("electron");
const aHref = document.querySelector("#aHref");
const jump = document.querySelector("#jump");

aHref.onclick = function (e) {
  e.preventDefault();
  const href = this.getAttribute("href");
  window.electron.openUrl(href);
};
jump.onclick = function (e) {
  e.preventDefault();
  const cmd = document
    .querySelector("#browser-list-fixed")
    .selectedOptions[0].getAttribute("data-cmd");
  const cwd = document
    .querySelector("#browser-list-fixed")
    .selectedOptions[0].getAttribute("data-cwd");
  console.log(cmd, cwd);
  window.electron.cpExec(cmd, cwd);
};

// Notification 渲染进程通知
const NOTIFICATION_TITLE = "Title";
const NOTIFICATION_BODY =
  "Notification from the Renderer process. Click to log to console.";
const CLICK_MESSAGE = "Notification clicked";

// new Notification(NOTIFICATION_TITLE, { body: NOTIFICATION_BODY }).onclick =
//   () => console.log(CLICK_MESSAGE);

document.querySelector("#mac-address").innerHTML = window.electron.mac;

window.electron.getDefBrowser(function (defBrowser) {
  console.log(defBrowser);
  const el = document.querySelector("#def-browser");
  if (el) {
    el.innerHTML = defBrowser.name;
  }
});
window.electron.getBrowserList(function (browserList) {
  console.log(browserList);
  const el = document.querySelector("#browser-list");
  if (el) {
    let _html = "";
    for (let i = 0; i < browserList.length; i++) {
      _html += `<option data-cmd="${browserList[i].cmd}" data-cwd="${browserList[i].cwd}">${browserList[i].name}</option>`;
    }
    el.innerHTML = _html;
  }
});

/* 客户端 */
const $ = require("jquery");
const crypto = require("crypto");
const moment = require("moment");

const Secret = "Y0h0s8xjb[J6kUuRJHLq%g1a]jNsMlOJ";
const Mac = require("getmac").default();
// 私有参数 排序
let comData = {
  Account: "popjsb_test",
  Mac: require("getmac").default(), // "40:8d:5c:59:0a:c5",
  Password: "tc2021",
};
// 公有参数
let comParams = {
  Site: 1,
  Key: "API_POP_CLIENT",
  // Timestamp: "",
  // Sign: "",
};

/* 对象转排序拼接字符串 */
function toSortString(data) {
  let str = "";
  const keys = Object.keys(data).sort();
  for (let i = 0; i < keys.length; i++) {
    str += keys[i] + data[keys[i]];
  }
  return str;
}

/* 生成sign */
function createSign(data) {
  console.log(Secret, toSortString(data));
  return crypto
    .createHash("md5")
    .update(Secret + toSortString(data))
    .digest("hex");
}

/* 绑定 */
function clientBinding() {
  const Timestamp = moment().format("YYYYMMDDHHmmss");
  const params = Object.assign(comParams, comData, {
    Timestamp: Timestamp,
  });
  params.Sign = createSign(params);
  $.ajax({
    url: "http://www.pop-fashion.com/interface/client/binding",
    data: params,
    success: function (res) {
      const code = res.querySelector("code").textContent;
      const message = res.querySelector("message").textContent;
      if (code != 0) {
        console.warn("binding", message);
      } else {
        console.log("binding", message);
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}
// clientBinding();

/* 登录 */
function clientLogin() {
  const Timestamp = moment().format("YYYYMMDDHHmmss");
  const params = Object.assign(comParams, comData, {
    Timestamp: Timestamp,
  });
  params.Sign = createSign(params);
  $.ajax({
    url: "http://www.pop-fashion.com/interface/client/login",
    data: params,
    success: function (res) {
      const code = res.querySelector("code").textContent;
      const message = res.querySelector("message").textContent;
      const url = res.querySelector("url").textContent;
      if (code != 0) {
        console.warn("binding", message);
      } else {
        console.log("binding", message);
        console.log(url);
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}
// clientLogin();

/* 日志 */
function clientLog() {
  const data = {
    Os: "Windows10",
    Account: comData.Account,
    Browsertype: "",
    Mac: Mac,
    Msg: "test",
  };
  const Timestamp = moment().format("YYYYMMDDHHmmss");
  const params = Object.assign(comParams, data, {
    Timestamp: Timestamp,
  });
  params.Sign = createSign(params);
  $.ajax({
    url: "http://www.pop-fashion.com/interface/client/log",
    data: params,
    success: function (res) {
      const code = res.querySelector("code").textContent;
      const message = res.querySelector("message").textContent;
      if (code != 0) {
        console.warn("binding", message);
      } else {
        console.log("binding", message);
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}
// clientLog();

/* 获取浏览器 */
function getBrowserList() {
  const Timestamp = moment().format("YYYYMMDDHHmmss");
  const params = Object.assign(comParams, {
    Timestamp: Timestamp,
  });
  params.Sign = createSign(params);
  $.ajax({
    url: "http://www.pop-fashion.com/interface/client/websites/",
    data: params,
    success: function (res) {
      const code = res.querySelector("code").textContent;
      const message = res.querySelector("message").textContent;
      const websiteEl = res.querySelectorAll("website");
      const website = {};
      websiteEl.forEach((v) => {
        website[v.getAttribute("name")] = v.textContent;
      });
      if (code != 0) {
        console.warn("binding", message);
      } else {
        console.log("binding", message);
        console.log(website);
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}
getBrowserList();
