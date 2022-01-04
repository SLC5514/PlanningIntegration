/*
 * @Author: SLC
 * @Date: 2021-12-30 15:57:01
 * @LastEditors: SLC
 * @LastEditTime: 2022-01-04 11:20:12
 * @Description: file content
 */

// import test from './test'
// const test = require('./test.js')

// console.log(test)

// module.exports = {
//   test
// }

// import foo from './foo.js';
// export default function () {
//   console.log(foo);
// }

// import { version } from '../package.json';

// export default function () {
//   console.log('version ' + version);
// }

import answer from 'the-answer';
import fntk from 'fntk';
import tstest from './tstest';

console.log(tstest)

export default () => {
  console.log('the answer is ' + answer);
  console.log(fntk)
}
