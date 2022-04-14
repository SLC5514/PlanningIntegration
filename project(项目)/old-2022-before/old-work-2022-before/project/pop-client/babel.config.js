/*
 * @Author: SLC
 * @Date: 2021-07-30 13:55:11
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-30 13:55:32
 * @Description: file content
 */

const presets = [
  [
    "@babel/env",
    {
      targets: {
        edge: "17",
        firefox: "60",
        chrome: "67",
        safari: "11.1",
      },
    },
  ],
];
module.exports = { presets };
