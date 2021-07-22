/*
 * @Author: SLC
 * @Date: 2021-07-22 16:34:02
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-22 16:34:42
 * @Description: file content
 */

window.addEventListener("DOMContentLoaded", () => {
  const replaceText = (selector, text) => {
    const element = document.getElementById(selector);
    if (element) element.innerText = text;
  };

  for (const dependency of ["chrome", "node", "electron"]) {
    replaceText(`${dependency}-version`, process.versions[dependency]);
  }
});
