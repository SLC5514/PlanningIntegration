import Vue from 'vue'
import Bus from 'vue'

Vue.prototype.$bus = new Bus()

/**
 * @description: 生成uuid
 * @return {String}
 */
Vue.prototype.$createUUID = function () {
  var d = new Date().getTime();
  try {
    if (performance && typeof performance.now === "function") {
      d += performance.now(); // use high-precision timer if available
    }
  } catch {}
	var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
		var r = (d + Math.random() * 16) % 16 | 0;
		d = Math.floor(d / 16);
		return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
	});
	return uuid;
}
