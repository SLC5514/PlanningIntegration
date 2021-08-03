/*
 * @Author: SLC
 * @Date: 2021-07-30 10:10:30
 * @LastEditors: SLC
 * @LastEditTime: 2021-08-03 10:36:13
 * @Description: file content
 */

export default (function (g) {
  const fntk = {
    /* 基础函数 ================================ */

    /**
     * @description: 0/正整数 校验函数
     * @param {Number|String} num 0/正整数
     * @return {Boolean} 校验状态
     */
    positiveIntegerOrZeroCheck: function (num) {
      if (parseFloat(num) !== parseInt(num) || num < 0) {
        return false;
      }
      return true;
    },

    /**
     * @description: 补零函数
     * @param {Number|String} num 待补数字 0/正整数
     * @param {Number|String} len 位数 0/正整数
     * @return {String} 补零字符
     */
    prefixInteger: function (num, len) {
      if (
        !fntk.positiveIntegerOrZeroCheck(num) ||
        !fntk.positiveIntegerOrZeroCheck(len)
      ) {
        console.warn("prefixInteger", "非0/正整数", num);
        return num;
      }
      return (Array(Number(len)).join(0) + Number(num)).slice(-len);
    },

    /**
     * @description: 阻止事件冒泡
     * @param {*} ev Event事件
     * @return {*}
     */
    stopBubble: function (ev) {
      const e = ev || window.event;
      if (e && e.stopPropagation) {
        e.stopPropagation();
      } else {
        window.event.cancelBubble = true;
      }
      return false;
    },

    /**
     * @description: 路由参数获取
     * @param {String} url href/search
     * @return {Object} 转化对象
     */
    getUrlParams: function (url) {
      const ogUrl = url ? url : location.href;
      const urlObj = ogUrl.replace(/[^\?]*\?/, "").split("&");
      let obj = {};
      for (let i = 0; i < urlObj.length; i++) {
        const arr = urlObj[i].split("=");
        obj[decodeURIComponent(arr[0])] = decodeURIComponent(arr[1]);
      }
      return obj;
    },

    /**
     * @description: 函数节流
     * @param {Function} method 带节流函数
     * @param {Object} context 执行上下文
     * @param {Array} arr 所需参数
     * @param {Number} times 节流时间
     * @return {*}
     */
    throttle: function (method, context, arr, times) {
      clearTimeout(method.tid);
      method.tid = setTimeout(
        function () {
          method.apply(context, arr);
        },
        times != undefined ? times : 50
      );
    },

    /* 浏览器存储 */
    /**
     * @description: 获取本地存储 Session
     * @param {String} session_name 字段名
     * @return {*} 存储值
     */
    getSession: function (session_name) {
      if (window.sessionStorage) {
        var val = sessionStorage.getItem(session_name);
        if (val === "undefined") {
          return "undefined";
        } else if (typeof val === "number") {
          return val;
        } else if (val) {
          return JSON.parse(val) ? JSON.parse(val) : "";
        }
      } else {
        return JSON.parse(fntk.getCookie(session_name))
          ? JSON.parse(fntk.getCookie(session_name))
          : "";
      }
    },
    /**
     * @description: 存储本地 Session
     * @param {String} session_name 字段名
     * @param {*} data 存储值
     * @return {*}
     */
    setSession: function (session_name, data) {
      if (window.sessionStorage) {
        sessionStorage.setItem(session_name, JSON.stringify(data));
      } else {
        fntk.setCookie(session_name, JSON.stringify(data), 10000);
      }
    },
    /**
     * @description: 删除本地存储 Session
     * @param {String} session_name 字段名
     * @return {*}
     */
    delSession: function (session_name) {
      if (window.sessionStorage) {
        if (sessionStorage.getItem(session_name)) {
          sessionStorage.removeItem(session_name);
        }
      } else {
        if (fntk.getCookie(session_name)) {
          fntk.setCookie(session_name, "", -1);
        }
      }
    },
    /**
     * @description: 获取本地存储 LocalSto
     * @param {String} sto_name 字段名
     * @return {*} 存储值
     */
    getLocalSto: function (sto_name) {
      if (window.localStorage) {
        var val = localStorage.getItem(sto_name);
        if (val === "undefined") {
          return "undefined";
        } else if (typeof val === "number") {
          return val;
        } else if (val) {
          return JSON.parse(val) ? JSON.parse(val) : "";
        }
      } else {
        return JSON.parse(fntk.getCookie(sto_name))
          ? JSON.parse(fntk.getCookie(sto_name))
          : "";
      }
    },
    /**
     * @description: 存储本地 LocalSto
     * @param {String} sto_name 字段名
     * @param {*} data 存储值
     * @return {*}
     */
    setLocalSto: function (sto_name, data) {
      if (window.localStorage) {
        localStorage.setItem(sto_name, JSON.stringify(data));
      } else {
        fntk.setCookie(sto_name, JSON.stringify(data), 10000);
      }
    },
    /**
     * @description: 删除本地存储 LocalSto
     * @param {String} sto_name 字段名
     * @return {*}
     */
    delLocalSto: function (sto_name) {
      if (window.localStorage) {
        if (localStorage.getItem(sto_name)) {
          localStorage.removeItem(sto_name);
        }
      } else {
        if (fntk.getCookie(sto_name)) {
          fntk.setCookie(sto_name, "", -1);
        }
      }
    },
    /**
     * @description: 设置cookie
     * @param {String} name 键名
     * @param {*} value 键值
     * @param {Number} Days 天数
     * @return {*}
     */
    setCookie: function (name, value, Days) {
      var exp = new Date();
      exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
      document.cookie =
        name +
        "=" +
        escape(value) +
        ";expires=" +
        exp.toGMTString() +
        ";path=/";
    },
    /**
     * @description: 获取cookie
     * @param {String} name 键名
     * @return {*} 存储值
     */
    getCookie: function (name) {
      var arr = document.cookie.match(
        new RegExp("(^| )" + name + "=([^;]*)(;|$)")
      );
      if (arr != null) {
        return unescape(arr[2]);
      } else {
        return null;
      }
    },

    /* 封装实例 ================================ */

    /**
     * @description: 时间格式化
     * @param {*} arguments 无参/Unix时间戳(value)/时间戳字符串(dateString)/Date成员
     * @return {Object} 实例
     */
    formatDate: function () {
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
          .replace(/MM/, fntk.prefixInteger(this._d.getMonth() + 1, 2))
          .replace(/M/, this._d.getMonth() + 1)
          .replace(/DD/, fntk.prefixInteger(this._d.getDate(), 2))
          .replace(/D/, this._d.getDate())
          .replace(/HH/, fntk.prefixInteger(this._d.getHours(), 2))
          .replace(/H/, this._d.getHours())
          .replace(
            /hh/,
            fntk.prefixInteger(this._24TO12(this._d.getHours()), 2)
          )
          .replace(/h/, this._24TO12(this._d.getHours()))
          .replace(/mm/, fntk.prefixInteger(this._d.getMinutes(), 2))
          .replace(/m/, this._d.getMinutes())
          .replace(/ss/, fntk.prefixInteger(this._d.getSeconds(), 2))
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
    },
  };
  if (typeof module !== "undefined" && module.exports) {
    module.exports = fntk;
  } else if (typeof define == "function" && define.amd) {
    define(function () {
      return fntk;
    });
  } else {
    g.fntk = fntk;
  }
  return fntk;
})(window, function () {
  "use strict";
});
