"use strict";

window._p_imgs = {}; // 各个标签下的图片

/**
 * @description: 设置右键菜单
 */
chrome.contextMenus.create({
  title: "提取本页图片",
  id: "get_page_imgs",
  contexts: ["all"],
});

/**
 * @description: 监听右键菜单点击事件
 */
chrome.contextMenus.onClicked.addListener(function (info, tab) {
  if (info.menuItemId == "get_page_imgs") {
    let tabImgs = window._p_imgs[tab.id];
    let momentCor = moment();
    const prefix = "POP图片批量下载";
    const pathTpl = "{page.host}/{YYYY-MM-DD}_{HH-mm-ss}/{page.title}";
    const title = _p_mural(tab.title).substr(0, 128);
    const host = _p_reed(tab.url);
    const pathName = pathTpl
      .replace(/\{page.title\}/gi, title)
      .replace(/\{page.host\}/gi, host)
      .replace(/\{YYYY-MM-DD\}/gi, momentCor.format("YYYY-MM-DD"))
      .replace(/\{HH-mm-ss\}/gi, momentCor.format("HH-mm-ss"));
    const pathUrl = prefix + "/" + pathName;

    let nameIdx = 0;
    let allImgs = tabImgs.length,
      imgCount = 0;
    for (let i = 0; i < tabImgs.length; i++) {
      blobUtil
        .imgSrcToBlob(tabImgs[i].url)
        .then(function (blob) {
          imgCount++;
          nameIdx++;
          // const blobUrl = blobUtil.createObjectURL(blob);
          const filename = _p_orchid(pathUrl) + "/" + nameIdx + "." + tabImgs[i].suffix;
          chrome.downloads.download({
            url: tabImgs[i].url,
            filename: filename,
            saveAs: false,
            conflictAction: "uniquify",
          });
          _p_complete(imgCount, allImgs);
        })
        .catch(function () {
          imgCount++;
          console.log(
            `%c[${i + 1}]图片获取失败 ${tabImgs[i].suffix}：\n${
              tabImgs[i].url
            }`,
            "color:red"
          );
          _p_complete(imgCount, allImgs);
        });
    }
  }
});

/**
 * @description: 释放URL对象
 */
chrome.downloads.onChanged.addListener(function (delta) {
  if (delta.state && delta.state.current === "complete") {
    chrome.downloads.search(
      {
        id: delta.id,
      },
      function (downloadItems) {
        downloadItems.forEach((downloadItem) => {
          const url = downloadItem.url;
          if (
            url.startsWith("blob:") &&
            url.indexOf("://" + chrome.runtime.id) > 0
          ) {
            window.URL.revokeObjectURL && window.URL.revokeObjectURL(url);
          }
        });
      }
    );
  }
});

/**
 * @description: 监听标签页请求
 */
chrome.webRequest.onHeadersReceived.addListener(
  function (details) {
    if (details.tabId < 0) return;
    let resHds = details.responseHeaders;
    for (let i = 0; i < resHds.length; ++i) {
      resHds[resHds[i].name] = resHds[i].value;
    }
    let ctnType = resHds["Content-Type"] || resHds["content-type"];
    if (ctnType) ctnType = ctnType.toLowerCase();
    if (
      details.type &&
      details.type == "image" &&
      ctnType &&
      ctnType.startsWith("image/")
    ) {
      if (!window._p_imgs[details.tabId]) window._p_imgs[details.tabId] = [];
      window._p_imgs[details.tabId].push({
        url: details.url,
        suffix: _p_suffix(ctnType.split(";")[0].split("/")[1]),
      });
    }
  },
  {
    urls: ["<all_urls>"],
  },
  ["blocking", "responseHeaders"]
);

/**
 * @description: 调整标题字符串
 * @param {String} parity
 * @param {Boolean} trifle
 * @return {String}
 */
function _p_mural(parity, trifle) {
  if (trifle) {
    return parity.replace(
      /[\u200B-\u200D\uFEFF\x00-\x1F\x7F\x80-\x9F\n\r\f\s\t\v\\:\*\|\?'"<>%&^`\!\$\.\~|  　]+/gi,
      "_"
    );
  } else {
    return parity.replace(
      /[\u200B-\u200D\uFEFF\x00-\x1F\x7F\x80-\x9F\n\r\f\s\t\v\\:\*\|\?'"<>%&^`\!\$\.\~|  　\/]+/gi,
      "_"
    );
  }
}

/**
 * @description: 截取路径字符串
 * @param {String} url
 * @return {String}
 */
function _p_reed(url) {
  let dowry = url.match(/^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/i);
  if (dowry) {
    return dowry[1];
  }
  return "";
}

/**
 * @description: 调整路径字符串
 * @param {String} slit
 * @return {String}
 */
function _p_orchid(slit) {
  if (slit) {
    return slit
      .replace(/[\\\/]+/gi, "/")
      .replace(/[\.]+/gi, ".")
      .replace(/^[\.]+/gi, "_")
      .replace(/[\.]+$/gi, "_")
      .replace(/\/[\.]+/gi, "/_")
      .replace(/[\.]+\//gi, "_/");
  }
  return slit;
}

/**
 * @description: 根据文件类型返回对应扩展名
 * @param {String} suffix
 * @return {String}
 */
function _p_suffix(suffix) {
  let str = suffix;
  switch (suffix) {
    case "x-icon":
      str = "ico";
      break;
    case "vnd.wap.wbmp":
      str = "wbmp";
      break;
    default:
      break;
  }
  return str;
}

/**
 * @description: 下载任务添加完成回调
 * @param {*} count
 * @param {*} len
 * @return {*}
 */
function _p_complete(count, len) {
  if (count === len) {
    chrome.notifications.create("", {
      type: "basic",
      iconUrl: "./images/icon512.png",
      title: "提示",
      message: "下载任务添加完成。 ^_^",
    });
  }
}
