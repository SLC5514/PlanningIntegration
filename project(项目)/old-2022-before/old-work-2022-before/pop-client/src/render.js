/*
 * @Author: SLC
 * @Date: 2021-11-10 18:15:09
 * @LastEditors: SLC
 * @LastEditTime: 2022-02-24 13:44:55
 * @Description: file content
 */

// Notification 渲染进程通知
// const NOTIFICATION_TITLE = "Title";
// const NOTIFICATION_BODY =
//   "Notification from the Renderer process. Click to log to console.";
// const CLICK_MESSAGE = "Notification clicked";

// new Notification(NOTIFICATION_TITLE, { body: NOTIFICATION_BODY }).onclick =
//   () => console.log(CLICK_MESSAGE);

// document.querySelector("#mac-address").innerHTML = window.electron.mac;

// window.electron.getBrowserList(function (browserList) {
//   console.log(browserList);
//   const el = document.querySelector("#browser-list");
//   if (el) {
//     let _html = "";
//     for (let i = 0; i < browserList.length; i++) {
//       _html += `<option data-cmd="${browserList[i].cmd}" data-cwd="${browserList[i].cwd}">${browserList[i].name}</option>`;
//     }
//     el.innerHTML = _html;
//   }
// });

/* 本地数据存储 */
const low = require('lowdb');
const FileSync = require('lowdb/adapters/FileSync');
const adapter = new FileSync('db.json');
const db = low(adapter);

db.defaults({
  Checked: 0,
  Account: '',
  Password: '',
  Website: 0
}).write();

/* 客户端逻辑 */
const { ipcRenderer } = require("electron");
const $ = require("jquery");
const os = require("os");
const crypto = require("crypto");
const moment = require("moment");

const formEl = $('.js-form-section');
const accountIpt = formEl.find('input[name="Account"]');
const passwordIpt = formEl.find('input[name="Password"]');
const websiteSel = formEl.find('select[name="website"]');
const remPasCheck = formEl.find('input[name="remember-password"]');

// let res = process.env.hasOwnProperty('PROCESSOR_ARCHITEW6432'); // true: x64 64bit  false: x86 32bit
const platform = os.platform();
const release = os.release();
let OsName = platform === 'darwin' ? 'Mac' : platform === 'win32' ? 'Windows' : platform === 'linux' ? 'Linux' : '';
OsName += ' v' + release;

const Secret = "Y0h0s8xjb[J6kUuRJHLq%g1a]jNsMlOJ";
let comParams = {
  Site: 0,
  Key: "API_POP_CLIENT"
};
let formData = {
  Account: "",
  Password: "",
  Mac: require("getmac").default(),
};
let selData = {
  cmd: '',
  cwd: '',
  type: ''
};
let defBrowser = '';
const winWidth = 300;
const winHeight = 410;
const winHeightToggle = 616;
// const cookieUrl = 'https://www.pop-fashion.com';

if ($('.js-browser-section').is(':visible')) {
  ipcRenderer.send('setBounds', {
    width: winWidth,
    height: winHeightToggle
  });
} else {
  ipcRenderer.send('setBounds', {
    width: winWidth,
    height: winHeight
  });
}
window.electron.getDefBrowser(function (defBrowser) {
  defBrowser = defBrowser.name;
});
// getWebsites();

// 账号密码回显
const dbState = db.getState();
remPasCheck.prop("checked", dbState['Checked'] === 1 ? true : false);
accountIpt.val(decrypt(dbState['Account'], Secret));
passwordIpt.val(decrypt(dbState['Password'], Secret));
websiteSel.val(dbState['Website']);
formData.Account = accountIpt.val();
formData.Password = passwordIpt.val();
/* sessionCookies('get', cookieUrl).then(res => {
  let count = 0;
  for (let i = 0; i < res.length; i++) {
    if (res[i].name === 'Account') {
      count++;
      accountIpt.val(res[i].value);
      continue;
    }
    if (res[i].name === 'Password') {
      count++;
      passwordIpt.val(res[i].value);
      continue;
    }
    if (res[i].name === 'Checked') {
      count++;
      remPasCheck.prop("checked", res[i].value === '1' ? true : false);
      continue;
    }
    if (count === 3) break;
  }
}); */

// 登录
$('.js-login').on('click', function() {
  if (!verify()) return;
  formData.Account = accountIpt.val();
  formData.Password = passwordIpt.val();
  comParams.Site = websiteSel.val();
  // 记住密码
  const checked = remPasCheck.is(':checked');
  if (checked) {
    db.setState({
      Checked: 1,
      Account: encrypt(formData.Account, Secret),
      Password: encrypt(formData.Password, Secret),
      Website: comParams.Site,
    }).write();
    // sessionCookies('set', cookieUrl, 'Checked', '1');
    // sessionCookies('set', cookieUrl, 'Account', formData.Account);
    // sessionCookies('set', cookieUrl, 'Password', formData.Password);
    // sessionCookies('set', cookieUrl, 'Website', comParams.Site);
  } else {
    db.setState({
      Checked: 0,
      Account: '',
      Password: '',
      Website: 0
    }).write();
    // sessionCookies('remove', cookieUrl, 'Checked');
    // sessionCookies('remove', cookieUrl, 'Account');
    // sessionCookies('remove', cookieUrl, 'Password');
    // sessionCookies('remove', cookieUrl, 'Website');
  }
  // 登录
  clientLogin();
})
// 绑定
$('.js-bind').on('click', function() {
  if (!verify()) return;
  formData.Account = accountIpt.val();
  formData.Password = passwordIpt.val();
  comParams.Site = websiteSel.val();
  clientBinding();
})
// 退出
$('.js-exit').on('click', function() {
  ipcRenderer.send('quit');
})
// 设置浏览器
$('.js-set-browser').on('click', function() {
  $('.js-browser-section').toggle();
  if ($('.js-browser-section').is(':visible')) {
    ipcRenderer.send('setBounds', {
      width: winWidth,
      height: winHeightToggle
    });
  } else {
    ipcRenderer.send('setBounds', {
      width: winWidth,
      height: winHeight
    });
  }
})
// 选择浏览器
$('.js-browser-list').on('click', 'li', function() {
  const self = $(this);
  self.addClass('on').siblings().removeClass('on');
  selData.cmd = self.data('cmd');
  selData.cwd = self.data('cwd');
  selData.type = self.text();
})

/* 校验 */
function verify() {
  if (!accountIpt.val()) {
    showMessageBox('error', '错误', '账号不能为空', ['ok']);
    return false;
  }
  if (!passwordIpt.val()) {
    showMessageBox('error', '错误', '密码不能为空', ['ok']);
    return false;
  }
  if (!parseInt(websiteSel.val())) {
    showMessageBox('error', '错误', '站点不能为空', ['ok']);
    return false;
  }
  return true;
}

/* 对象转排序拼接字符串 */
function toSortString(data) {
  let str = "";
  const keys = Object.keys(data).sort();
  for (let i = 0; i < keys.length; i++) {
    str += keys[i] + data[keys[i]];
  }
  return str;
}

/* 加密 */
function encrypt(str, key) {
  if (typeof str == 'object') str = JSON.stringify(str);
  try {
    const cipher = crypto.createCipheriv('aes-256-cbc', key, key.substring(0, 16));
    cipher.setAutoPadding(true);
    return cipher.update(String(str), 'utf8', 'hex') + cipher.final('hex');
  } catch(e) {
    console.error(e)
    return '';
  }
}

/* 解密 */
function decrypt(str, key) {
  try {
    const decipher = crypto.createDecipheriv('aes-256-cbc', key, key.substring(0, 16));
    decipher.setAutoPadding(true);
    return decipher.update(String(str), 'hex', 'utf8') + decipher.final('utf8');
  } catch(e) {
    console.error(e)
    return '';
  }
}

/* 生成sign签名 */
function createSign(data) {
  return crypto
    .createHash("md5")
    .update(Secret + toSortString(data))
    .digest("hex");
}

/* 绑定 */
function clientBinding() {
  const Timestamp = moment().format("YYYYMMDDHHmmss");
  const params = Object.assign({}, comParams, formData, {
    Timestamp: Timestamp,
  });
  params.Sign = createSign(params);
  $.ajax({
    url: "https://www.pop-fashion.com/interface/client/binding",
    data: params,
    success: function (res) {
      const code = res.querySelector("code").textContent;
      const message = res.querySelector("message").textContent;
      if (code != 0) {
        showMessageBox('warning', '提示', message, ['ok']);
      } else {
        showMessageBox('info', '提示', message, ['ok']);
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}

/* 登录 */
function clientLogin() {
  const Timestamp = moment().format("YYYYMMDDHHmmss");
  const params = Object.assign({}, comParams, formData, {
    Timestamp: Timestamp,
  });
  params.Sign = createSign(params);
  $.ajax({
    url: "https://www.pop-fashion.com/interface/client/login",
    data: params,
    success: function (res) {
      const code = res.querySelector("code").textContent;
      const message = res.querySelector("message").textContent;
      if (code != 0) {
        showMessageBox('warning', '提示', message, ['ok']);
      } else {
        const url = res.querySelector("url").textContent;
        window.electron.cpExec(selData.cmd, selData.cwd, url, function(error, stdout, stderr) {
          if (error) {
            console.error(`exec error: ${error}`);
            return;
          }
          if (stdout) console.log(`stdout: ${stdout}`);
          if (stderr) console.error(`stderr: ${stderr}`);
        });
      }
      clientLog(message);
    },
    error: function (err) {
      console.log(err);
    },
  });
}

/* 日志 */
function clientLog(message) {
  const data = {
    Os: OsName,
    Account: formData.Account,
    Browsertype: selData.type || defBrowser,
    Mac: formData.Mac,
    Msg: message,
  };
  const Timestamp = moment().format("YYYYMMDDHHmmss");
  const params = Object.assign({}, comParams, data, {
    Timestamp: Timestamp,
  });
  params.Sign = createSign(params);
  $.ajax({
    url: "https://www.pop-fashion.com/interface/client/log",
    data: params,
    success: function (res) {
      const code = res.querySelector("code").textContent;
      const message = res.querySelector("message").textContent;
      if (code != 0) {
        console.warn("log", message);
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}

/* 获取站点 */
function getWebsites() {
  const Timestamp = moment().format("YYYYMMDDHHmmss");
  const params = Object.assign({}, comParams, {
    Timestamp: Timestamp,
  });
  params.Sign = createSign(params);
  $.ajax({
    url: "https://www.pop-fashion.com/interface/client/websites/",
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
        console.warn('getWebsites', message);
      } else {
        let html = '<option value="0">请选择</option>';
        for (let i in website) {
          html += `<option value="${i}">${website[i]}</option>`;
        }
        websiteSel.html(html);

        // 站点回显
        const dbState = db.getState();
        websiteSel.find(`option[value=${dbState['Website']}]`).prop('selected', true);
        comParams.Site = websiteSel.val();
        /* sessionCookies('get', cookieUrl, 'Website').then(res => {
          let count = 0;
          for (let i = 0; i < res.length; i++) {
            if (res[i].name === 'Website') {
              count++;
              websiteSel.find(`option[value=${res[i].value}]`).prop('selected', true);
              continue;
            }
            if (count === 1) break;
          }
        }); */
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}

/* 消息弹框 */
function showMessageBox(type, title, message, buttons, callback) {
  ipcRenderer.send('showMessageBox', {
    type: type,
    title: title,
    message: message,
    buttons: buttons,
    callback: callback
  });
}

/* 数据存储 */
async function sessionCookies(type, url, name, value, days) {
  let res;
  try {
    res = await ipcRenderer.invoke('sessionCookies', {
      type: type,
      url: url,
      name: name,
      value: value,
      days: days
    })
  } catch (error) {
    console.error(error);
  }
  return res;
}
