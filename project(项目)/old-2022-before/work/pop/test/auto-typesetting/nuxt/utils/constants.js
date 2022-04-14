export const ERROR_CODE_OK = 0;
export const ERROR_CODE_ERROR = 1;

// 生成一个空的格子图片数据
export const genEmptyPhotoData = function() {
  return {
    isLocal: false, // 是否本地上传的图片
    width: 0, // 图片宽
    height: 0, // 图片高
    x: 0, // 图片左上角相对格子左上角 left
    y: 0, // 图片左上角相对格子左上角 top
    link: "", // 点击格子后跳转链接
    linkType: "1", // 链接类型: 1-网页, 2-文件
    description: "", // 图片描述
    big: "", // 大图
    small: "", // 小图
    brand: "", // 品牌名
    id: "", // 图片ID，款式图为对应款式id，本地图为上传后返回的id
    scale: 1.0, // 缩放比, 显示大小 : 实际大小
    rotation: 0, // 旋转角度
    rotationX: 0, // 是否沿X轴翻转
    rotationY: 0 // 是否沿Y轴翻转
  };
};

// 生成一个空的格子数据
export const genEmptyGridData = function() {
  return {
    width: 0, // 格子宽
    height: 0, // 格子高
    x: 0, // 格子左边界位置 left
    y: 0, // 格子上边界位置 top
    rw: 1,
    rh: 1,
    rx: 1,
    ry: 1,
    photo: genEmptyPhotoData()
  };
};
