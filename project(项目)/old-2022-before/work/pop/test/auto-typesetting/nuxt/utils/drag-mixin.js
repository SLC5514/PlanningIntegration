/* 外部 setReportItemData $createUUID scale */

let dragDom = null;

let dragConfig = {
  isPreDrag: false, // 准备拖拽
  isDrag: false, // 正式拖拽
  origin: {
    clientY: 0, // 鼠标按下时候时候值
    clientX: 0,
    layerX: 0, // 鼠标.x 相对于元素左上角.left 的偏移
    layerY: 0 // 鼠标.y 相对于元素左上角.top  的偏移
  }
};

class Drag {
  constructor(options) {
    this.mousedownMixin = options.mousedownMixin;
    this.mousemoveMixin = options.mousemoveMixin;
    this.mouseupMixin = options.mouseupMixin;

    this._mousedown = this._mousedown.bind(this);
    this._mousemove = this._mousemove.bind(this);
    this._mouseup = this._mouseup.bind(this);
  }

  start(e) {
    this._mousedown(e);
  }

  _mousedown(e) {
    this.mousedownMixin(e);
    this.toggleListener("add");
  }

  _mousemove(e) {
    this.mousemoveMixin(e);
  }

  _mouseup(e) {
    this.mouseupMixin(e);
    this.toggleListener("remove");
  }

  toggleListener(action) {
    document[`${action}EventListener`]("mousemove", this._mousemove);
    document[`${action}EventListener`]("mouseup", this._mouseup);
  }
}

export default {
  data() {
    return {
      copyTargetMixin: null
    };
  },
  methods: {
    // 鼠标按下 初始化类
    handleDragStartFromMixin(e, element) {
      // 0 为 左键点击
      if (e.button !== 0) return;
      this.copyTargetMixin = e.target;
      this.copyTargetMixin.classList.add('grabbing');
      document.body.classList.add('cursor-grabbing');
      if (dragDom) {
        document.body.removeChild(dragDom);
        dragDom = null;
      }
      this.dragElement = element;
      dragDom = this.copyTargetMixin.cloneNode(true);
      document.body.appendChild(dragDom);

      new Drag({
        mousedownMixin: this.mousedownMixin,
        mousemoveMixin: this.mousemoveMixin,
        mouseupMixin: this.mouseupMixin
      }).start(e);
    },
    // 鼠标按下
    mousedownMixin(e) {
      // 鼠标.x 相对于元素左上角 的偏移
      const { layerX, layerY } = e;
      dragConfig.origin.layerX = layerX;
      dragConfig.origin.layerY = layerY;
      dragConfig.origin.clientX = e.clientX;
      dragConfig.origin.clientY = e.clientY;

      dragDom.style.position = "absolute";
      dragDom.style.left = e.clientX - layerX + "px";
      dragDom.style.top = e.clientY - layerY + "px";
      dragDom.classList.add("dragging-dom-ele", "hidden");

      dragConfig.isPreDrag = true;
    },
    // 组件拖拽中
    mousemoveMixin(e) {
      dragDom.classList.remove("hidden");
      const { layerX, layerY } = dragConfig.origin;
      dragDom.style.left = e.clientX - layerX + "px";
      dragDom.style.top = e.clientY - layerY + "px";
    },
    // 鼠标抬起
    mouseupMixin(e) {
      document.body.removeChild(dragDom);
      dragDom = null;
      this.copyTargetMixin.classList.remove('grabbing');
      document.body.classList.remove('cursor-grabbing');

      if (this.$parent.hoverReportIdx < 0) return;
      const canMousedown = this.checkCanMousedownMixin(e, { minOffset: 10 });
      if (!canMousedown) return;
      const canvasWrapper = document.querySelectorAll('.paper-page')[this.$parent.hoverReportIdx];
      if (!canvasWrapper) return;

      const { layerX, layerY } = dragConfig.origin;
      const position = canvasWrapper.getBoundingClientRect();
      const scale = this.scale / 100;

      this.dragElement &&
        this.setReportItemData({ keys: [this.$parent.hoverReportIdx, 'elements'], val: {
          ...this.dragElement,
          uuid: this.$createUUID(),
          style: {
            ...this.dragElement.style,
            left: (e.clientX - layerX - position.left) / scale,
            top: (e.clientY - layerY - position.top) / scale,
          }
        } });
    },
    // 边界判断
    checkCanMousedownMixin(e, { minOffsetX, minOffsetY, minOffset }) {
      const offsetX = e.clientX - dragConfig.origin.clientX;
      const offsetY = e.clientY - dragConfig.origin.clientY;

      return (
        offsetX >= (minOffsetX || minOffset) ||
        offsetY >= (minOffsetY || minOffset)
      );
    }
  }
};
