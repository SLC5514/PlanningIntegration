/*
 * @Author: SLC
 * @Date: 2021-07-27 11:27:46
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-29 15:37:59
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
  const cmd = document.querySelector('#browser-list-fixed').selectedOptions[0].getAttribute('data-cmd');
  const cwd = document.querySelector('#browser-list-fixed').selectedOptions[0].getAttribute('data-cwd');
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

document.querySelector('#mac-address').innerHTML = window.electron.mac;

window.electron.getDefBrowser(function(defBrowser) {
  console.log(defBrowser)
  const el = document.querySelector('#def-browser');
  if (el) {
    el.innerHTML = defBrowser.name;
  }
})
window.electron.getBrowserList(function(browserList) {
  console.log(browserList)
  const el = document.querySelector('#browser-list');
  if (el) {
    let _html = '';
    for (let i = 0; i < browserList.length; i++) {
      _html += `<option data-cmd="${browserList[i].cmd}" data-cwd="${browserList[i].cwd}">${browserList[i].name}</option>`;
    }
    el.innerHTML = _html;
  }
})

var crypt = new JSEncrypt();
var enc = crypt.encrypt(encodeURIComponent(JSON.stringify({
  Site: 1,
  Account: 1,
  Password: 1,
  Mac: 1,
})))
console.log(enc);
var dec = crypt.decrypt(enc);
console.log(JSON.parse(decodeURIComponent(dec)))
