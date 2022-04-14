/*
 * @Author: SLC
 * @Date: 2021-07-22 16:39:10
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-22 17:34:25
 * @Description: file content
 */

// Notification 渲染进程通知
const NOTIFICATION_TITLE = "Title";
const NOTIFICATION_BODY =
  "Notification from the Renderer process. Click to log to console.";
const CLICK_MESSAGE = "Notification clicked";

new Notification(NOTIFICATION_TITLE, { body: NOTIFICATION_BODY }).onclick =
  () => console.log(CLICK_MESSAGE);

console.log(window.myAPI);
