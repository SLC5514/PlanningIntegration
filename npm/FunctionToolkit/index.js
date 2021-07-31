/*
 * @Author: SLC
 * @Date: 2021-07-30 10:10:30
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-31 15:21:40
 * @Description: file content
 */

/* 基础函数 ================================ */

/**
 * @description: 0/正整数 校验函数
 * @param {Number|String} num 0/正整数
 * @return {Boolean} 校验状态
 */
export const positiveIntegerOrZeroCheck = function (num) {
  if (parseFloat(num) !== parseInt(num) || num < 0) {
    return false;
  }
  return true;
};

/**
 * @description: 补零函数
 * @param {Number|String} num 待补数字 0/正整数
 * @param {Number|String} len 位数 0/正整数
 * @return {String} 补零字符
 */
export const prefixInteger = function (num, len) {
  if (!positiveIntegerOrZeroCheck(num) || !positiveIntegerOrZeroCheck(len)) {
    console.warn("prefixInteger", "非0/正整数", num);
    return num;
  }
  return (Array(Number(len)).join(0) + Number(num)).slice(-len);
};

/**
 * @description: 阻止事件冒泡
 * @param {*} ev Event事件
 * @return {*}
 */
export const stopBubble = function (ev) {
  const e = ev || window.event;
  if (e && e.stopPropagation) {
    e.stopPropagation();
  } else {
    window.event.cancelBubble = true;
  }
  return false;
};

export const getUrlParams = function (url) {
  const ogUrl = url ? url : location.href;
  console.log(ogUrl)
}

/* 封装实例 ================================ */

/**
 * @description: 时间格式化
 * @param {*} arguments 无参/Unix时间戳(value)/时间戳字符串(dateString)/Date成员
 * @return {Object} 实例
 */
export const formatDate = function () {
  function moment() {
    this._d = new Date(...arguments);
  }
  /**
   * @description: 格式化函数
   * @param {String} inputString 字符串格式
   * @return {String} 时间字符串
   */
  moment.prototype.format = function (inputString) {
    return inputString
      .replace(/YYYY/, this._d.getFullYear())
      .replace(/YY/, this._d.getFullYear().toString().substr(-2))
      .replace(/MM/, prefixInteger(this._d.getMonth() + 1, 2))
      .replace(/M/, this._d.getMonth() + 1)
      .replace(/DD/, prefixInteger(this._d.getDate(), 2))
      .replace(/D/, this._d.getDate())
      .replace(/HH/, prefixInteger(this._d.getHours(), 2))
      .replace(/H/, this._d.getHours())
      .replace(/hh/, prefixInteger(this._24TO12(this._d.getHours()), 2))
      .replace(/h/, this._24TO12(this._d.getHours()))
      .replace(/mm/, prefixInteger(this._d.getMinutes(), 2))
      .replace(/m/, this._d.getMinutes())
      .replace(/ss/, prefixInteger(this._d.getSeconds(), 2))
      .replace(/s/, this._d.getSeconds());
  };
  /**
   * @description: 时间制转换函数
   * @param {Number} num 24时间制数字
   * @return {Number} 12时间制数字
   */
  moment.prototype._24TO12 = function (num) {
    return num > 12 ? num - 12 : num;
  };
  return new moment(...arguments);
};

/* 默认导出 ================================ */

export default {
  /* 基础函数 ================================ */
  positiveIntegerOrZeroCheck,
  prefixInteger,
  stopBubble,
  getUrlParams,
  /* 封装实例 ================================ */
  formatDate,
};
