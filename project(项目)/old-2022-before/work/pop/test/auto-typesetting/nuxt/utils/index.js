// 滚轮适配
/* (function(window, undefined) {
  let _eventCompat = function(event) {
    let type = event.type;
    if (type == "DOMMouseScroll" || type == "mousewheel") {
      event.delta = event.wheelDelta
        ? event.wheelDelta / 120
        : -(event.detail || 0) / 3;
    }
    if (event.srcElement && !event.target) {
      event.target = event.srcElement;
    }
    if (!event.preventDefault && event.returnValue !== undefined) {
      event.preventDefault = function() {
        event.returnValue = false;
      };
    }
    // ......其他一些兼容性处理
    return event;
  };
  if (window.addEventListener) {
    return function(el, type, fn, capture) {
      if (type === "mousewheel" && document.mozFullScreen !== undefined) {
        type = "DOMMouseScroll";
      }
      el.addEventListener(
        type,
        function(event) {
          fn.call(this, _eventCompat(event));
        },
        capture || false
      );
    };
  } else if (window.attachEvent) {
    return function(el, type, fn) { // , capture
      el.attachEvent("on" + type, function(event) {
        event = event || window.event;
        fn.call(el, _eventCompat(event));
      });
    };
  }
  return function() {};
})(window); */

/**
 * @description: 函数节流
 * @param {Function} method 函数
 * @param {Object} context 上下文
 * @param {Array} arr 参数
 * @param {Number} times 时间节点
 */
export const throttle = function(method, context, arr, times) {
  clearTimeout(method.tid);
  method.tid = setTimeout(
    function() {
      method.apply(context, arr);
    },
    times != undefined ? times : 50
  );
};

/**
 * @description: 生成uuid方法
 * @return {String}
 */
export const createUUID = function () {
	var d = new Date().getTime();
	if (window.performance && typeof window.performance.now === "function") {
		d += performance.now(); // use high-precision timer if available
	}
	var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
		var r = (d + Math.random() * 16) % 16 | 0;
		d = Math.floor(d / 16);
		return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
	});
	return uuid;
}

/**
 * @description: 深拷贝
 * @param {Object/Array} 拷贝对象
 * @param {Array} 缓存数组
 * @return {Object}
 */
export const deepClone = function (obj, cache = []) {
	// typeof [] => 'object'
	// typeof {} => 'object'
	if (obj === null || typeof obj !== 'object') {
		return obj
	}
	// 如果传入的对象与缓存的相等, 则递归结束, 这样防止循环
	/**
	 * 类似下面这种
	 * var a = {b:1}
	 * a.c = a
	 * 资料: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Errors/Cyclic_object_value
	 */
	const hit = cache.filter(c => c.original === obj)[0]
	if (hit) {
		return hit.copy
	}

	const copy = Array.isArray(obj) ? [] : {}
	// 将copy首先放入cache, 因为我们需要在递归deepCopy的时候引用它
	cache.push({
		original: obj,
		copy
	})
	Object.keys(obj).forEach(key => {
		copy[key] = deepClone(obj[key], cache)
	})

	return copy
}
