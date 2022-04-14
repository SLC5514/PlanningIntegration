/*
 * @Author: SLC
 * @Date: 2021-07-19 16:49:18
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-20 14:57:57
 * @Description: file content
 */

/* 批量下载 jszip+fileSaver */
function batchDownload(params) {
  this.fileList = params.data || [];
  this.progress = params.progress || function () {};
  this.error = params.error || function () {};
  this._log = params.log || false;
  this._folder = params.folder || "压缩文件";
  this._log && console.log("初始化");
}
batchDownload.prototype.downLoad = function () {
  var _this = this;
  this._reDownCount = 0;
  this._jsZip = new JSZip();
  this._base64Arr = new Array(this.fileList.length);
  this.getBase64ByAjax(function (length, count) {
    if (!length) {
      console.log("无文件!");
      _this.error("无文件!");
      return;
    }
    var isComplete = length === count && length > 0,
      speed = (count / (length || 1)) * 100;
    _this._log && console.log("已完成数:" + count);
    if (isComplete) {
      _this.saveZipFile(function (status) {
        _this.progress(speed, isComplete, status);
      });
    }
    _this.progress(speed, isComplete);
  });
};
batchDownload.prototype.getBase64ByAjax = function (callback) {
  var _this = this;
  if (this.fileList.length === 0) {
    callback();
    return;
  }
  for (var i = 0; i < this.fileList.length; i++) {
    this.fileList[i].suffix = this.fileList[i].url.match(/\.(\w+)$/)[0] || "";
    var xhr = new XMLHttpRequest();
    xhr.open("get", this.fileList[i].url, true);
    xhr.diyData = {
      thisIndex: i,
    };
    xhr.responseType = "blob";
    //xhr.setRequestHeader("", "");
    xhr.onload = function () {
      var fileIndex = this.diyData.thisIndex;
      var fileStatus = this.status;
      _this._log && console.log("正在请求:", this.diyData.thisIndex);
      _this.fileList[fileIndex].status = fileStatus;
      if (fileStatus == 200) {
        var blob = this.response;
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onload = function (e) {
          _this._log && console.log("状态：" + fileIndex + "-" + fileStatus);
          _this._base64Arr[fileIndex] = e.target.result.split(",")[1];
          _this._reDownCount = _this._reDownCount + 1;
          callback(_this.fileList.length, _this._reDownCount);
        };
      } else {
        _this._log && console.log("状态：" + fileIndex + "-" + fileStatus);
        _this.fileList[fileIndex].name =
          _this.fileList[fileIndex].name +
          _this.fileList[fileIndex].suffix +
          "(!!此文件下载错误" +
          fileStatus +
          ")";
        _this.fileList[fileIndex].suffix = ".txt";
        _this._base64Arr[fileIndex] = "";
        _this._reDownCount = _this._reDownCount + 1;
        callback(_this.fileList.length, _this._reDownCount);
      }
    };
    xhr.send();
  }
};
batchDownload.prototype.saveZipFile = function (callback) {
  var _this = this;
  for (var i = 0; i < this._base64Arr.length; i++) {
    this._jsZip.file(
      this.fileList[i].name + this.fileList[i].suffix,
      this._base64Arr[i],
      {
        base64: true,
      }
    );
  }
  this._jsZip
    .generateAsync({
      type: "blob",
    })
    .then(function (content) {
      saveAs(content, _this._folder + ".zip");
      callback(true);
    })
    .catch(function (err) {
      _this.error(err);
      callback(false);
    });
};

/* 使用 */
var fileList = [
  {
    name: "小老头",
    url: "https://hx-pro.oss-cn-hangzhou.aliyuncs.com/68bb0ac4-af6e-41fa-9a91-462f128c82b5.jpg",
  },
  {
    name: "表情",
    url: "https://hx-pro.oss-cn-hangzhou.aliyuncs.com/48ccd15f-c12f-4346-b761-048b6f564fce.png",
  },
  {
    name: "pdf文件",
    url: "https://hx-pro.oss-cn-hangzhou.aliyuncs.com/b1c6bf5e-b9e5-4381-bdb4-de440f674b12.pdf",
  },
  {
    name: "excel文件",
    url: "https://hx-pro.oss-cn-hangzhou.aliyuncs.com/1f60a6de-28d4-4e65-b213-c27a50dcd813.xlsx",
  },
  {
    name: "故意错误文件",
    url: "https://hx-pro.oss-cn-hangzhou.aliyuncs.com/404.png",
  },
  {
    name: "test",
    url: "https://imgf2.pop-fashion.com/fashion/stylegraphic/2021071600000916360/women/2021SS/159/top/t_shirt/202107160012755567/big/0ed995c0812c2683f2368ea4d79ab01b.jpg",
  },
  {
    name: "test1",
    url: "https://imgf2.pop-fashion.com/fashion/graphic/2021071404-SH5989/small/05.jpg",
  },
  {
    name: "test2",
    url: "https://imgf3.pop-fashion.com/fashion/graphic/2021071404-SH5989/small/04.jpg",
  },
]; // 元数据

var batch = new batchDownload({
  log: true,
  folder: "资料包",
  data: fileList,
  progress: function (speed, isComplete, isCompression) {
    console.log(speed + "%", isComplete, isCompression);
    if (isComplete && isCompression) {
      $("#xiazai").text("状态：压缩完成!");
      $("#progress>div").css("background-color", "green");
    } else if (isComplete && isCompression === false) {
      $("#xiazai").text("状态：压缩失败!");
    } else if (isComplete && isCompression === undefined) {
      $("#xiazai").text("状态：准备压缩");
    } else {
      $("#xiazai").append(".");
    }
    $("#progress>div").css("width", speed + "%");
  },
  error: function (err) {
    $("#xiazai").text("错误：" + err);
  },
});
console.log(batch);

$("#downloadBtn").on("click", function () {
  $("#xiazai").text("状态：开始下载");
  batch.downLoad();
});
$("#updateBtn").on("click", function () {
  batch.fileList = [];
});
