<template>
  <div
    :class="{
      'edit-shape-com': !disable,
      'on': elActive,
      'is-move': isEleMove || isRotation || isClipMove,
      'is-clip': isClipImgActive,
      'is-text-edit': isTextEdit,
      ['rotation_' + rotationType]: true,
    }"
    :ref="item.uuid"
    :style="eleStyle"
    @click.stop="() => { return false; }"
    @mousedown.left.stop="handleMouseDownOnElement"
  >
    <!-- 格子插槽 -->
    <slot name="grid-box"></slot>
    <!-- 文本值 -->
    <p class="text" ref="text" v-if="item.type === 'text' && isTextEdit"
      :contenteditable="isTextEdit"
      v-html="item.text"
      @click.stop="() => { return false; }"
      @mousedown.left.stop="() => { return false; }"
    ></p>
    <p class="text" v-else-if="item.type === 'text'"
      contenteditable="false"
      v-html="item.text"
      @dblclick.stop="textDblclick">
    </p>
    <!-- 拖拽点 -->
    <i
      :class="'editor-grip ' + item + (point === item ? ' active' : '')"
      v-for="item in elActive ? pointList : []"
      :key="item"
      @mousedown.left.stop="handleMouseDownOnPoint($event, item)"
      ><b :style="`transform: scale(${1 - (defScale - 1)})`"></b
    ></i>
    <!-- 旋转 -->
    <i
      class="editor-rotator"
      v-if="elActive && !isEleMove && !isClipImgActive"
      @mousedown.left.stop="handleMouseDownOnRotator"
      ><b :style="`transform: rotate(${-item.style.rotate}deg);`"
        ><span v-if="isRotation">{{ item.style.rotate }}°</span></b
      ></i
    >
    <!-- 图片虚影 -->
    <img
      v-if="isClipImgActive"
      class="element-image-img clip-img"
      :src="item.clip.image"
      :style="
        defClip && {
          width: defClip.w + 'px',
          height: defClip.h + 'px',
          left: defClip.x + 'px',
          top: defClip.y + 'px'
        }
      "
      @mousedown="
        e => {
          if (isClipImgActive) {
            e.stopPropagation();
            e.preventDefault();
          }
        }
      "
    />
    <!-- 图片裁剪画布 -->
    <div
      :class="{ 'element-image-img-wrap': true, 'clip-mask': isClipImgActive }"
      v-if="item.type === 'image'"
      @mousedown.left="clipImageMouseDown"
    >
      <img
        class="element-image-img"
        :src="item.clip.image"
        :style="
          defClip && {
            width: defClip.w + 'px',
            height: defClip.h + 'px',
            left: defClip.x + 'px',
            top: defClip.y + 'px'
          }
        "
      />
      <!-- 裁剪格子 -->
      <div :class="'editor-croper-grid editor-mask-editor-grid' + (isClipImgActive && (isClipMove || isEleMove) ? ' active' : '')">
        <i class="editor-croper-grid-x1"></i>
        <i class="editor-croper-grid-x2"></i>
        <i class="editor-croper-grid-y1"></i>
        <i class="editor-croper-grid-y2"></i>
      </div>
    </div>
    <!-- 缩放滑块 -->
    <div
      class="toolbar-wrap"
      v-if="isClipImgActive"
      v-show="!isClipMove"
      @mousedown.stop="
        () => {
          return false;
        }
      "
      :style="`transform: scale(${1 - defScale + 1}) translate(-50%, -50%) rotate(${-item.style.rotate}deg);` + (item.style.rotate ? `margin-left: ${clipBarLeft}px;margin-top: ${clipBarTop}px;` : `margin-top: -${item.style.height / 2 + 30}px`)"
    >
      <span>缩放</span>
      <el-slider
        v-model="scaleSlider"
        :min="100"
        :max="scaleSliderMax"
        :format-tooltip="formatTooltip"
        @input="scaleSliderInput"
        @change="scaleSliderChange"
      ></el-slider>
      <i class="toolbar-pipe is-full"></i>
      <button @click="clipReset">重置</button>
      <i class="toolbar-pipe"></i>
      <button @click="clipCancel">取消</button>
      <i class="toolbar-pipe"></i>
      <button class="el-button--text" @click="clipSliderSuc">完成</button>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import { mapGetters, mapMutations } from "vuex";

const defScaleSliderMax = 300;

// 获取网页元素的相对位置
function getElementViewLeft(element) {
  let actualLeft = element.offsetLeft;
  let current = element.offsetParent;
  let elementScrollLeft = document.querySelector('.paper-main').scrollLeft || 0;

  while (current !== null) {
    actualLeft += current.offsetLeft;
    current = current.offsetParent;
  }

  // if (document.compatMode == "BackCompat") {
  //   elementScrollLeft = document.body.scrollLeft;
  // } else {
  //   elementScrollLeft = document.documentElement.scrollLeft;
  // }

  return actualLeft - elementScrollLeft;
}
function getElementViewTop(element) {
  let actualTop = element.offsetTop;
  let current = element.offsetParent;
  let elementScrollTop = document.querySelector('.paper-main').scrollTop || 0;

  while (current !== null) {
    actualTop += current.offsetTop;
    current = current.offsetParent;
  }

  // if (document.compatMode == "BackCompat") {
  //   elementScrollTop = document.body.scrollTop;
  // } else {
  //   elementScrollTop = document.documentElement.scrollTop;
  // }

  return actualTop - elementScrollTop;
}

// 获取三点夹角 {x,y} 中心A
function getPointAngle(A, B, C) {
  const AB = Math.sqrt(Math.pow(A.x - B.x, 2) + Math.pow(A.y - B.y, 2));
  const AC = Math.sqrt(Math.pow(A.x - C.x, 2) + Math.pow(A.y - C.y, 2));
  const BC = Math.sqrt(Math.pow(B.x - C.x, 2) + Math.pow(B.y - C.y, 2));
  const cos =
    (Math.pow(AB, 2) + Math.pow(AC, 2) - Math.pow(BC, 2)) / (2 * AB * AC);
  let angle = Math.round((Math.acos(cos) * 180) / Math.PI);
  if (C.x > A.x) angle = -angle;
  return angle;
}
// 获取一点旋转后坐标 {x,y} 中心A
function getAnglePoint(A, B, angle) {
  // 求C点
  const AB = Math.sqrt(Math.pow(A.x - B.x, 2) + Math.pow(A.y - B.y, 2));
  const cx = A.x + Math.sin(2 * Math.PI / 360 * 0) * AB;
  const cy = A.y + Math.cos(2 * Math.PI / 360 * 0) * AB;
  const C = { x: cx, y: cy };
  // 求rotate
  const AC = Math.sqrt(Math.pow(A.x - C.x, 2) + Math.pow(A.y - C.y, 2));
  const BC = Math.sqrt(Math.pow(B.x - C.x, 2) + Math.pow(B.y - C.y, 2));
  const cos = (Math.pow(AB, 2) + Math.pow(AC, 2) - Math.pow(BC, 2)) / (2 * AB * AC);
  let rotate = Math.acos(cos) * 180 / Math.PI;
  const type = B.x < A.x;
  const x = (A.x * 100 + Number((Math.sin(2 * Math.PI / 360 * (180 + angle + (type ? rotate : -rotate))) * AB).toFixed(2)) * 100) / 100;
  const y = (A.y * 100 + Number((Math.cos(2 * Math.PI / 360 * (angle + (type ? rotate : -rotate))) * AB).toFixed(2)) * 100) / 100;
  // 求D点
  return { x: x, y: y };
}

export default {
  name: "EditShapeCom",
  props: {
    item: {
      type: Object
    },
    disable: {
      type: Boolean,
      default: false
    },
    pageIdx: {
      type: Number
    },
    elIdx: {
      type: Number
    },
    gridIdx: {
      type: Number
    },
    referElements: {
      type: Array
    }
  },
  data() {
    return {
      // 拖拽点
      pointList: ["w", "e", "n", "s", "nw", "ne", "sw", "se"],
      // 文本编辑中
      isTextEdit: false,
      // 元素拖动中
      isEleMove: false,
      // 元素旋转状态
      isRotation: false,
      // 元素旋转中
      isEleRotator: false,
      // 中心坐标
      rotateCtn: { x: 0, y: 0 },
      // 裁剪拖动中
      isClipMove: false,
      // 裁剪缩放滑块
      scaleSlider: 100,
      // 裁剪缩放滑块最大值
      scaleSliderMax: defScaleSliderMax,
      // 裁剪工具栏旋转位置
      clipBarLeft: 0,
      clipBarTop: 0,
      // 默认裁剪数据
      defStyle: null,
      defClip: null,
      // 点击拖拽点
      point: "",
    };
  },
  computed: {
    ...mapGetters([
      "activeElementUUID",
      "scale",
      "gridScale",
      "maxElZIndex",
      "reportData",
      "isHotShift",
      "isClipImg"
    ]),
    // 格式化画布缩放值
    defScale() {
      return this.scale / 100;
    },
    // 元素是否选中
    elActive() {
      return this.activeElementUUID === this.item.uuid && !this.disable;
    },
    // 计算元素样式
    eleStyle() {
      let style = {};
      const needUnitStr = [
        "width",
        "height",
        "top",
        "left",
        "paddingTop",
        "paddingLeft",
        "paddingRight",
        "paddingBottom",
        "marginTop",
        "marginLeft",
        "marginRight",
        "marginBottom",
        "borderWidth",
        "fontSize",
        "borderRadius",
        "letterSpacing"
      ];
      const noNeedUnitStr = ["x", "y"];
      for (let key in this.item.style) {
        if (
          needUnitStr.includes(key) ||
          (key === "lineHeight" && this.item.style[key] > 2)
        ) {
          if (
            (key === "width" || key === "height") &&
            this.item.style[key] === "auto"
          ) {
            // 属性兼容
            style[key] = this.item.style[key];
          } else {
            style[key] = this.item.style[key] * this.defGridScale + "px";
          }
        } else if (noNeedUnitStr.includes(key)) {
          continue;
        } else if (key === "rotate") {
          style.transform = `rotate(${this.item.style[key]}deg)`;
        } else if (key === "backgroundImage") {
          style.backgroundImage = `url(${this.item.style[key]})`;
        } else {
          style[key] = this.item.style[key];
        }
      }
      return style;
    },
    // 格子缩放值（非格子元素为1）
    defGridScale() {
      return this.item.type === "grid" ? this.gridScale : 1;
    },
    // 最小宽高
    minWH() {
      return 10 / this.defGridScale;
    },
    // 正在裁剪图片组件
    isClipImgActive() {
      return (
        this.isClipImg &&
        this.elActive &&
        (this.item.type === "grid" || this.item.type === "image")
      );
    },
    // 元素样式层次
    styleKeys() {
      if (this.item.type === "grid") {
        return [ this.pageIdx, "elements", this.elIdx, "grids", this.gridIdx, "style" ];
      }
      return [this.pageIdx, "elements", this.elIdx, "style"];
    },
    // 元素层次
    elementKeys() {
      if (this.item.type === "grid") {
        return [this.pageIdx, "elements", this.elIdx, "grids", this.gridIdx];
      }
      return [this.pageIdx, "elements", this.elIdx];
    },
    // 旋转角度状态
    rotationType() {
      if (!this.elActive) return 0;
      if (this.item.style.rotate < 22.5 && this.item.style.rotate > -22.5) return 0;
      if (this.item.style.rotate < 67.5 && this.item.style.rotate > 22.5) return 45;
      if (this.item.style.rotate < 112.5 && this.item.style.rotate > 67.5) return 90;
      if (this.item.style.rotate < 157.5 && this.item.style.rotate > 112.5) return 135;
      if (this.item.style.rotate > 157.5 || this.item.style.rotate < -157.5) return 180;
      if (this.item.style.rotate > -157.5 && this.item.style.rotate < -112.5) return -135;
      if (this.item.style.rotate > -112.5 && this.item.style.rotate < -67.5) return -90;
      if (this.item.style.rotate > -67.5 && this.item.style.rotate < -22.5) return -45;
    }
  },
  mounted() {
    if (this.disable || this.item.type === "grid-box") {
      return;
    }
    // 初始化宽高
    if (
      this.item.type !== "grid" &&
      (!this.item.style["width"] || this.item.style["width"] === "auto")
    ) {
      this.setReportItemData({
        keys: this.styleKeys,
        key: "width",
        val: this.$refs[this.item.uuid].clientWidth
      });
    }
    if (
      this.item.type !== "grid" &&
      (!this.item.style["height"] || this.item.style["height"] === "auto")
    ) {
      this.setReportItemData({
        keys: this.styleKeys,
        key: "height",
        val: this.$refs[this.item.uuid].clientHeight
      });
    }
    // 初始化中心点
    this.rotateCtn.x =
      getElementViewLeft(this.$refs[this.item.uuid]) + this.item.style.width / 2;
    this.rotateCtn.y =
      getElementViewTop(this.$refs[this.item.uuid]) + this.item.style.height / 2;
    // 监听裁剪
    if (this.item.type === "grid" || this.item.type === "image") {
      this.$bus.$on("clipImage", this.clipImage);
      this.$bus.$on("clipSliderSuc", this.clipSliderSuc);
      // 初始化裁剪宽高
      this.setReportItemData({
        keys: [...this.elementKeys, "clip"],
        key: "w",
        val: this.item.style["width"]
      });
      this.setReportItemData({
        keys: [...this.elementKeys, "clip"],
        key: "h",
        val: this.item.style["height"]
      });
      this.clipReset();
    }
  },
  methods: {
    ...mapMutations([
      "setActiveElementUUID",
      "setReportItemData",
      "setIsClipImg"
    ]),
    // 获取point计算样式
    getPointStyle(point) {
      const pos = this.item.style;
      const width = pos.width * this.defGridScale;
      const height = pos.height * this.defGridScale;
      let hasT = /n/.test(point);
      let hasB = /s/.test(point);
      let hasL = /w/.test(point);
      let hasR = /e/.test(point);
      let newLeft = 0;
      let newTop = 0;
      if (point.length === 2) {
        newLeft = hasL ? 0 : width;
        newTop = hasT ? 0 : height;
      } else {
        // 上下点，宽度固定在中间
        if (hasT || hasB) {
          newLeft = (width - 10) / 2;
          newTop = hasT ? 0 : height;
        }
        // 左右点，高度固定在中间
        if (hasL || hasR) {
          newLeft = hasL ? 0 : width;
          newTop = (height - 10) / 2;
        }
      }
      return {
        marginLeft: hasL || hasR ? "-5px" : 0,
        marginTop: hasT || hasB ? "-5px" : 0,
        left: `${newLeft}px`,
        top: `${newTop}px`,
        // cursor: point.split('').reverse().map(m => this.directionKey[m]).join('') + '-resize',
        cursor: point + "-resize",
        zIndex: this.maxElZIndex + 1,
        transform: `scale(${1 - this.defScale + 1})`
      };
    },
    // 鼠标拖拽元素
    handleMouseDownOnElement(e) {
      if (this.disable) return;
      if (this.isClipImg && !this.elActive) {
        this.$bus.$emit("clipSliderSuc");
        return;
      }
      this.setActiveElementUUID(this.item.uuid);
      this.$bus.$emit("setColNavType", this.item.type);
      this.$bus.$emit("initReferElements", this.item.uuid);
      const pos = { ...this.item.style };
      const startX = e.clientX / this.defScale;
      const startY = e.clientY / this.defScale;
      const startTop = pos.top;
      const startLeft = pos.left;
      const move = moveEvent => {
        moveEvent.stopPropagation();
        moveEvent.preventDefault();
        this.isEleMove = true;
        const currX = moveEvent.clientX / this.defScale;
        const currY = moveEvent.clientY / this.defScale;
        // 初始化中心点
        this.rotateCtn.x =
          getElementViewLeft(this.$refs[this.item.uuid]) + pos.width / 2;
        this.rotateCtn.y =
          getElementViewTop(this.$refs[this.item.uuid]) + pos.height / 2;
        this.setReportItemData({
          keys: this.styleKeys,
          key: "left",
          val: (currX - startX) / this.defGridScale + startLeft
        });
        this.setReportItemData({
          keys: this.styleKeys,
          key: "top",
          val: (currY - startY) / this.defGridScale + startTop
        });
        // 计算参考线
        this.calcVHLine(false);
      };
      const up = () => {
        // 记录历史数据
        if (this.isEleMove) {
          console.log("记录");
        }
        this.isEleMove = false;
        this.$bus.$emit("clearVLine");
        this.$bus.$emit("clearHLine");
        document.removeEventListener("mousemove", move, true);
        document.removeEventListener("mouseup", up, true);
      };
      document.addEventListener("mousemove", move, true);
      document.addEventListener("mouseup", up, true);
    },
    // 鼠标拖拽元素point
    handleMouseDownOnPoint(e, point) {
      if (this.disable) return;
      this.setActiveElementUUID(this.item.uuid);
      this.$bus.$emit("setColNavType", this.item.type);
      this.point = point;
      const pos = { ...this.item.style };
      const posClip = { ...this.defClip };
      let width = pos.width;
      let height = pos.height;
      const ratio = width / height;
      let startTop = pos.top;
      let startLeft = pos.left;
      let startX = e.clientX / this.defScale;
      let startY = e.clientY / this.defScale;
      let move = moveEvent => {
        this.isEleMove = true;
        let currX = moveEvent.clientX / this.defScale;
        let currY = moveEvent.clientY / this.defScale;
        let disY = currY - startY;
        let disX = currX - startX;
        let hasT = /n/.test(point);
        let hasB = /s/.test(point);
        let hasL = /w/.test(point);
        let hasR = /e/.test(point);
        // 旋转计算
        // const rotationType = Math.abs(this.rotationType);
        // 边界限制
        // if (disX > (width - this.minWH) && (this.rotationType === 0 ? hasL : this.rotationType === 180 ? hasR : false)) disX = width - this.minWH;
        // if (disY > (height - this.minWH) && (this.rotationType === 0 ? hasT : this.rotationType === 180 ? hasB : false)) disY = height - this.minWH;
        // if (disX > (height - this.minWH) && (this.rotationType === 90 ? hasB : this.rotationType === -90 ? hasT : false)) disX = height - this.minWH;
        // if (disY > (width - this.minWH) && (this.rotationType === 90 ? hasL : this.rotationType === -90 ? hasR : false)) disY = width - this.minWH;
        // let newWidth = width + (
        //   hasL ? (this.rotationType === 0 ? -disX : this.rotationType === 180 ? disX : false) :
        //   hasR ? (this.rotationType === 0 ? disX : this.rotationType === 180 ? -disX : false) : 0
        // ) / this.defGridScale;
        // let newHeight = height + (
        //   hasT ? (this.rotationType === 0 ? -disY : this.rotationType === 180 ? disY : false) :
        //   hasB ? (this.rotationType === 0 ? disY : this.rotationType === 180 ? -disY : false) : 0
        // ) / this.defGridScale;
        if (disX > (width - this.minWH) && hasL) disX = width - this.minWH;
        if (disY > (height - this.minWH) && hasT) disY = height - this.minWH;
        let newWidth = width + (hasL ? -disX : hasR ? disX : 0) / this.defGridScale;
        let newHeight = height + (hasT ? -disY : hasB ? disY : 0) / this.defGridScale;
        // 最小值计算
        if (newWidth < this.minWH) newWidth = this.minWH;
        if (newHeight < this.minWH) newHeight = this.minWH;
        // 等比计算
        if (
          this.isClipImgActive || (this.isHotShift && !(point === "n" || point === "s" || point === "w" || point === "e"))
        ) {
          const h = newWidth / ratio;
          disY += newHeight - h;
          newHeight = h;
        }
        // 元素计算修改
        this.setReportItemData({
          keys: this.styleKeys,
          key: "width",
          val: newWidth > 0 ? newWidth : 0
        });
        this.setReportItemData({
          keys: this.styleKeys,
          key: "height",
          val: newHeight > 0 ? newHeight : 0
        });
        this.setReportItemData({
          keys: this.styleKeys,
          key: "left",
          // val: startLeft + ((this.rotationType === 0 ? hasL : this.rotationType === 180 ? hasR : false) ? disX / this.defGridScale : 0)
          val: startLeft + (hasL ? disX / this.defGridScale : 0)
        });
        this.setReportItemData({
          keys: this.styleKeys,
          key: "top",
          // val: startTop + ((this.rotationType === 0 ? hasT : this.rotationType === 180 ? hasB : false) ? disY / this.defGridScale : 0)
          val: startTop + (hasT ? disY / this.defGridScale : 0)
        });
        // 图片计算修改
        if (this.item.type === "grid" || this.item.type === "image") {
          let newW = (newWidth * this.scaleSlider) / 100;
          let newH = (newHeight * this.scaleSlider) / 100;
          if (
            (this.isHotShift || this.isClipImgActive) &&
            !(point === "n" || point === "s" || point === "w" || point === "e")
          ) {
            // 等比
            if (newW < 0) newW = 0;
            if (newH < 0) newH = 0;
            newH = newW / ratio;
          }
          if (this.isClipImgActive) {
            // 裁剪中
            this.isClipMove = true;
            this.defClip.x = posClip.x - (hasL ? disX / this.defGridScale : 0);
            this.defClip.y = posClip.y - (hasT ? disY / this.defGridScale : 0);
          } else {
            this.defClip.w = newW > 0 ? newW : 0;
            this.defClip.h = newH > 0 ? newH : 0;
            this.defClip.x = posClip.x * (newW / posClip.w);
            this.defClip.y = posClip.y * (newH / posClip.h);
            this.setReportItemData({
              keys: this.elementKeys,
              key: "clip",
              val: { ...this.defClip }
            });
            // 计算参考线
            this.calcVHLine(true, { hasT, hasB, hasL, hasR });
          }
        } else {
          // 计算参考线
          this.calcVHLine(true, { hasT, hasB, hasL, hasR });
        }
      };
      let up = () => {
        if (this.isClipImgActive) {
          this.isClipMove = false;
          let r = 1;
          const rw = posClip.w / this.item.style.width;
          const rh = posClip.h / this.item.style.height;
          if (rw < rh) {
            r = rw;
          } else {
            r = rh;
          }
          const rt = r * 100;
          if (rt > defScaleSliderMax) {
            this.scaleSliderMax = rt;
          } else if (rt < defScaleSliderMax) {
            this.scaleSliderMax = defScaleSliderMax;
          }
          this.scaleSlider = rt;
          this.$nextTick(() => {
            this.scaleSliderChange();
          });
        } else {
          // 记录历史数据
          if (this.isEleMove) {
            console.log("记录");
          }
        }
        this.point = "";
        this.isEleMove = false;
        this.$bus.$emit("clearVLine");
        this.$bus.$emit("clearHLine");
        document.removeEventListener("mousemove", move);
        document.removeEventListener("mouseup", up);
      };
      document.addEventListener("mousemove", move);
      document.addEventListener("mouseup", up);
    },
    // 旋转
    handleMouseDownOnRotator(e) {
      if (this.disable) return;
      this.isRotation = true;
      // 初始化中心点
      this.rotateCtn.x =
        getElementViewLeft(this.$refs[this.item.uuid]) + this.item.style.width / 2;
      this.rotateCtn.y =
        getElementViewTop(this.$refs[this.item.uuid]) + this.item.style.height / 2;
      const move = moveEvent => {
        moveEvent.stopPropagation();
        moveEvent.preventDefault();
        this.isEleRotator = true;
        const currX = moveEvent.clientX / this.defScale;
        const currY = moveEvent.clientY / this.defScale;
        const rotate = getPointAngle(
          { x: this.rotateCtn.x, y: this.rotateCtn.y },
          { x: this.rotateCtn.x, y: this.rotateCtn.y + 100 },
          { x: currX, y: currY }
        );
        this.setReportItemData({
          keys: this.styleKeys,
          key: "rotate",
          val: rotate
        });
      };
      const up = () => {
        // 记录历史数据
        if (this.isEleRotator) {
          console.log("记录");
        }
        this.isRotation = false;
        this.isEleRotator = false;
        document.removeEventListener("mousemove", move, true);
        document.removeEventListener("mouseup", up, true);
      };
      document.addEventListener("mousemove", move, true);
      document.addEventListener("mouseup", up, true);
    },
    // 获取旋转后四角相对画布坐标
    rotateCoordinates(item, type) {
      const gridScale = item.type === "grid" ? this.gridScale : 1;
      const pos = { ...item.style };
      const eleft = pos.left * gridScale;
      const etop = pos.top * gridScale;
      const ewidth = pos.width * gridScale;
      const eheight = pos.height * gridScale;
      const ctnxy = { x: eleft + ewidth / 2, y: etop + eheight / 2 };
      // 左上
      const ltxy = getAnglePoint(
        ctnxy,
        { x: eleft, y: etop },
        pos.rotate
      );
      // 右上
      const rtxy = getAnglePoint(
        ctnxy,
        { x: eleft + ewidth, y: etop },
        pos.rotate
      );
      // 左下
      const lbxy = getAnglePoint(
        ctnxy,
        { x: eleft, y: etop + eheight },
        pos.rotate
      );
      // 右下
      const rbxy = getAnglePoint(
        ctnxy,
        { x: eleft + ewidth, y: etop + eheight },
        pos.rotate
      );
      const exCoords = [ltxy.x, rtxy.x, ctnxy.x, lbxy.x, rbxy.x];
      const eyCoords = [ltxy.y, rtxy.y, ctnxy.y, lbxy.y, rbxy.y];
      if (type) return [{...ltxy}, {...rtxy}, {...ctnxy}, {...lbxy}, {...rbxy}];
      return { x: exCoords, y: eyCoords };
    },
    // 计算参考线
    calcVHLine(isPointMove, point) {
      let referElementsXCoords = [];
      let referElementsYCoords = [];
      this.referElements.forEach(v => {
        const gridScale = v.type === "grid" ? this.gridScale : 1;
        const width = v.style.width * gridScale;
        const left = v.style.left * gridScale || 0;
        const height = v.style.height * gridScale;
        const top = v.style.top * gridScale || 0;
        if (v.style.rotate) {
          const rotate = Math.abs(v.style.rotate);
          if (rotate === 0 || rotate === 180) {
            referElementsXCoords = [
              ...referElementsXCoords,
              left + width,
              left + width / 2,
              left
            ];
            referElementsYCoords = [
              ...referElementsYCoords,
              top + height,
              top + height / 2,
              top
            ];
          } else if (rotate === 90) {
            const l = (width - height) / 2;
            const t = (height - width) / 2;
            referElementsXCoords = [
              ...referElementsXCoords,
              left + l + height,
              left + l + height / 2,
              left + l
            ];
            referElementsYCoords = [
              ...referElementsYCoords,
              top + t + width,
              top + t + width / 2,
              top + t
            ];
          } else {
            const rc = this.rotateCoordinates(v);
            referElementsXCoords = [
              ...referElementsXCoords,
              ...rc.x
            ];
            referElementsYCoords = [
              ...referElementsYCoords,
              ...rc.y
            ];
          }
        } else {
          referElementsXCoords = [
            ...referElementsXCoords,
            left,
            left + width / 2,
            left + width
          ];
          referElementsYCoords = [
            ...referElementsYCoords,
            top,
            top + height / 2,
            top + height
          ];
        }
      });

      const pos = { ...this.item.style };
      const posClip = { ...this.defClip };
      const eleft = pos.left * this.defGridScale;
      const etop = pos.top * this.defGridScale;
      const ewidth = pos.width * this.defGridScale;
      const eheight = pos.height * this.defGridScale;
      let exCoords = [];
      let eyCoords = [];
      const rotate = Math.abs(pos.rotate);
      if (point) {
        // if (rotate === 0 || rotate === 180) {
        //   exCoords.push(eleft + ewidth / 2);
        //   (rotate === 0 ? point.hasR : rotate === 180 ? point.hasL : false) && exCoords.push(eleft + ewidth);
        //   (rotate === 0 ? point.hasL : rotate === 180 ? point.hasR : false) && exCoords.push(eleft);
        //   eyCoords.push(etop + eheight / 2);
        //   (rotate === 0 ? point.hasB : rotate === 180 ? point.hasT : false) && eyCoords.push(etop + eheight);
        //   (rotate === 0 ? point.hasT : rotate === 180 ? point.hasB : false) && eyCoords.push(etop);
        // } else if (rotate === 90) {
        // } else {
        // }
        // 拖拽点优化判断
        exCoords.push(eleft + ewidth / 2);
        point.hasR && exCoords.push(eleft + ewidth);
        point.hasL && exCoords.push(eleft);
        eyCoords.push(etop + eheight / 2);
        point.hasB && eyCoords.push(etop + eheight);
        point.hasT && eyCoords.push(etop);
      } else {
        if (rotate === 0 || rotate === 180) {
          exCoords = [eleft + ewidth, eleft + ewidth / 2, eleft];
          eyCoords = [etop + eheight, etop + eheight / 2, etop];
        } else if (rotate === 90) {
          const l = (ewidth - eheight) / 2;
          const t = (eheight - ewidth) / 2;
          exCoords = [eleft + l + eheight, eleft + l + eheight / 2, eleft + l];
          eyCoords = [etop + t + ewidth, etop + t + ewidth / 2, etop + t];
        } else {
          const rc = this.rotateCoordinates(this.item);
          exCoords = rc.x;
          eyCoords = rc.y;
        }
      }
      let hasVLine = false;
      let hasHLine = false;
      const bd = 5;
      exCoords.forEach((eX, eXIdx) => {
        referElementsXCoords.forEach((referX, referXIdx) => {
          let offset = referX - eX;
          if (Math.abs(offset) <= bd) {
            if (isPointMove) {
              let newEW = pos.width + (point.hasR ? offset : -offset) / this.defGridScale;
              if (newEW < this.minWH) newEW = this.minWH;
              // 修改元素样式
              this.setReportItemData({
                keys: this.styleKeys,
                key: "width",
                val: newEW
              });
              !point.hasR &&
                this.setReportItemData({
                  keys: this.styleKeys,
                  key: "left",
                  val: pos.left + offset / this.defGridScale
                });
              if (this.item.type === "grid" || this.item.type === "image") {
                let newW = newEW * (this.scaleSlider / 100);
                this.defClip.w = newW > 0 ? newW : 0;
                this.defClip.x = posClip.x * (newW / posClip.w);
                this.setReportItemData({
                  keys: this.elementKeys,
                  key: "clip",
                  val: { ...this.defClip }
                });
              }
            } else {
              // 修改元素样式
              this.setReportItemData({
                keys: this.styleKeys,
                key: "left",
                val: pos.left + offset / this.defGridScale
              });
            }
            this.$bus.$emit("drawVLine", referX);
            hasVLine = true;
          }
        });
      });
      eyCoords.forEach((eY, eYIdx) => {
        referElementsYCoords.forEach((referY, referYIdx) => {
          let offset = referY - eY;
          if (Math.abs(offset) <= bd) {
            if (isPointMove) {
              let newEH = pos.height + (point.hasB ? offset : -offset) / this.defGridScale;
              if (newEH < this.minWH) newEH = this.minWH;
              // 修改元素样式
              this.setReportItemData({
                keys: this.styleKeys,
                key: "height",
                val: newEH
              });
              !point.hasB &&
                this.setReportItemData({
                  keys: this.styleKeys,
                  key: "top",
                  val: pos.top + offset / this.defGridScale
                });
              if (this.item.type === "grid" || this.item.type === "image") {
                let newH = newEH * (this.scaleSlider / 100);
                this.defClip.h = newH > 0 ? newH : 0;
                this.defClip.y = posClip.y * (newH / posClip.h);
                this.setReportItemData({
                  keys: this.elementKeys,
                  key: "clip",
                  val: { ...this.defClip }
                });
              }
            } else {
              // 修改元素样式
              this.setReportItemData({
                keys: this.styleKeys,
                key: "top",
                val: pos.top + offset / this.defGridScale
              });
            }
            this.$bus.$emit("drawHLine", referY);
            hasHLine = true;
          }
        });
      });
      if (!hasVLine) {
        this.$bus.$emit("clearVLine");
      }
      if (!hasHLine) {
        this.$bus.$emit("clearHLine");
      }
    },
    // 裁剪图片
    clipImage() {
      if (this.disable || !this.elActive) return;
      this.setIsClipImg(true);
      this.defStyle = { ...this.item.style };
      this.defClip = { ...this.item.clip };
      this.clipBarLeft = 0;
      this.clipBarTop = 0;
      if (!this.defStyle.rotate) return;
      const rc = this.rotateCoordinates(this.item, true);
      const exCoordsMin = [...rc].sort((a, b) => a.x - b.x);
      const eyCoordsMin = [...rc].sort((a, b) => a.y - b.y);
      const C = eyCoordsMin[2].y - eyCoordsMin[0].y + 30;
      this.clipBarLeft = C * Math.cos(2 * Math.PI / 360 * (90 - Math.abs(this.defStyle.rotate)));
      if (this.defStyle.rotate > 0) this.clipBarLeft = -this.clipBarLeft;
      this.clipBarTop = -C * Math.cos(2 * Math.PI / 360 * Math.abs(this.defStyle.rotate));
    },
    // 裁剪拖拽元素
    clipImageMouseDown(e) {
      if (this.disable) return;
      if (!this.isClipImgActive) return;
      e.stopPropagation();
      const pos = { ...this.item.style };
      const posClip = { ...this.defClip };
      const startX = e.clientX / this.defScale;
      const startY = e.clientY / this.defScale;
      const startTop = posClip.y;
      const startLeft = posClip.x;
      const move = moveEvent => {
        moveEvent.stopPropagation();
        moveEvent.preventDefault();
        const currX = moveEvent.clientX / this.defScale;
        const currY = moveEvent.clientY / this.defScale;
        this.isClipMove = true;
        this.defClip.x = (currX - startX) / this.defGridScale + startLeft;
        this.defClip.y = (currY - startY) / this.defGridScale + startTop;
      };
      const up = () => {
        this.isClipMove = false;
        this.scaleSliderChange();
        document.removeEventListener("mousemove", move, true);
        document.removeEventListener("mouseup", up, true);
      };
      document.addEventListener("mousemove", move, true);
      document.addEventListener("mouseup", up, true);
    },
    // 缩放滑块格式化
    formatTooltip(val) {
      if (val >= defScaleSliderMax) {
        return "已达最大值";
      }
      return parseInt(val) + "%";
    },
    // 缩放滑块修改
    scaleSliderInput(val) {
      const rt = val / 100;
      const pos = { ...this.item.style };
      const posClip = { ...this.defClip };
      this.defClip.scale = rt;
      this.defClip.w = pos.width * rt;
      this.defClip.h = pos.height * rt;
      this.defClip.x = posClip.x + (posClip.w - this.defClip.w) / 2;
      this.defClip.y = posClip.y + (posClip.h - this.defClip.h) / 2;
    },
    // 边界判断
    scaleSliderChange() {
      if (this.defClip.x > 0) this.defClip.x = 0;
      if (this.defClip.y > 0) this.defClip.y = 0;
      const l = this.item.style.width - (this.defClip.x + this.defClip.w);
      const t = this.item.style.height - (this.defClip.y + this.defClip.h);
      if (l > 0) this.defClip.x = this.defClip.x + l;
      if (t > 0) this.defClip.y = this.defClip.y + t;
    },
    // 裁剪完成
    clipSliderSuc() {
      if (!this.elActive) return;
      this.setReportItemData({
        keys: this.elementKeys,
        key: "clip",
        val: { ...this.defClip }
      });
      this.setIsClipImg(false);
      console.log("记录");
    },
    // 裁剪重置
    clipReset() {
      this.scaleSliderMax = defScaleSliderMax;
      this.scaleSlider = ((this.item.clip && this.item.clip.scale) || 1) * 100;
      if (this.defStyle) {
        this.setReportItemData({
          keys: this.elementKeys,
          key: "style",
          val: { ...this.defStyle }
        });
      }
      this.defClip = { ...this.item.clip };
    },
    // 裁剪取消
    clipCancel() {
      this.clipReset();
      this.setIsClipImg(false);
    },
    // 双击文本
    textDblclick() {
      this.isTextEdit = true;
      this.$nextTick(() => {
        this.$refs['text'].focus();
        document.execCommand('selectAll', false);
        let blur = () => {
          this.setReportItemData({
            keys: this.elementKeys,
            key: "text",
            val: this.$refs['text'].innerHTML.replace(/<p><\/p>/g, '')
          });
          this.isTextEdit = false;
          this.$refs['text'].removeEventListener("blur", blur);
        }
        this.$refs['text'].addEventListener('blur', blur);
      })
    },
  }
};
</script>

<style lang="scss" scoped>
.edit-shape-com {
  position: relative;
  cursor: move;
  .text {
    width: 100%;
    height: 100%;
    outline: none;
  }
  &.is-text-edit {
    .text {
      cursor: text;
    }
    .editor-grip {
      padding: 0;
    }
  }
  &:hover {
    outline: 1px solid #6ccfff;
  }
  &.on {
    outline: 2px solid #6ccfff;
    user-select: none;
    z-index: 5;
  }
  &.is-move {
    outline: 1px solid rgba(108, 207, 255, 0.3);
    .editor-grip {
      visibility: hidden;
      &.active {
        visibility: visible;
      }
    }
  }
  &.is-clip {
    outline: 2px solid #6ccfff;
    .editor-grip {
      margin: 0;
      background: none;
      border: none;
      box-shadow: none;
      border-radius: 0;
      filter: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg"><filter id="filter"><feGaussianBlur in="SourceAlpha" stdDeviation="2" /><feOffset dx="1" dy="1" result="offsetblur" /><feFlood flood-color="rgba(0,0,0,0.3)" /><feComposite in2="offsetblur" operator="in" /><feMerge><feMergeNode /><feMergeNode in="SourceGraphic" /></feMerge></filter></svg>#filter');
      filter: drop-shadow(0 0 2px rgba(0, 0, 0, 0.3));
      b {
        display: none;
      }
      &:before,
      &:after {
        content: "";
        position: absolute;
        background: #fff;
        border-radius: 2px;
      }
      // 四边
      &.n:after,
      &.s:after {
        display: none;
      }
      &.w:before,
      &.e:before {
        display: none;
      }
      &.n:before {
        left: 0;
        top: 50%;
        margin-top: -3px;
      }
      &.s:before {
        left: 0;
        bottom: 50%;
        margin-bottom: -3px;
      }
      &.w:after {
        top: 0;
        left: 50%;
        margin-left: -3px;
      }
      &.e:after {
        top: 0;
        right: 50%;
        margin-right: -3px;
      }
      // 四角
      &.nw:after,
      &.nw:before {
        left: 50%;
        top: 50%;
        margin-left: -3px;
        margin-top: -3px;
      }
      &.ne:after,
      &.ne:before {
        right: 50%;
        top: 50%;
        margin-right: -3px;
        margin-top: -3px;
      }
      &.sw:after,
      &.sw:before {
        left: 50%;
        bottom: 50%;
        margin-left: -3px;
        margin-bottom: -3px;
      }
      &.se:after,
      &.se:before {
        right: 50%;
        bottom: 50%;
        margin-right: -3px;
        margin-bottom: -3px;
      }
      // 边角宽高
      &.nw:before,
      &.ne:before,
      &.sw:before,
      &.se:before,
      &.n:before,
      &.s:before {
        width: 100%;
        height: 4px;
      }
      &.nw:after,
      &.ne:after,
      &.sw:after,
      &.se:after,
      &.w:after,
      &.e:after {
        width: 4px;
        height: 100%;
      }
    }
    .element-image-img {
      outline: 1px dashed #666;
    }
  }
  // 拖拽点
  .editor-grip {
    padding: 8px;
    position: absolute;
    left: 0;
    top: 0;
    z-index: 5;
    transform: translate(-50%, -50%);
    b {
      box-sizing: border-box;
      display: block;
      background: #fff;
      border: 1px solid #c0c5cf;
      box-shadow: 0 0 2px 0 rgba(86, 90, 98, 0.2);
      border-radius: 6px;
    }
    // 四边
    &.n {
      cursor: ns-resize;
      left: 50%;
      top: 0;
      margin-top: -1px;
    }
    &.s {
      cursor: ns-resize;
      left: 50%;
      top: 100%;
      margin-top: 1px;
    }
    &.w {
      cursor: ew-resize;
      top: 50%;
      left: 0;
      margin-left: -1px;
    }
    &.e {
      cursor: ew-resize;
      top: 50%;
      left: 100%;
      margin-left: 1px;
    }
    // 四角
    &.nw {
      cursor: nwse-resize;
      margin-left: -1px;
      margin-top: -1px;
    }
    &.ne {
      cursor: nesw-resize;
      left: 100%;
      margin-top: -1px;
      margin-left: 1px;
    }
    &.sw {
      cursor: nesw-resize;
      top: 100%;
      margin-left: -1px;
      margin-top: 1px;
    }
    &.se {
      cursor: nwse-resize;
      left: 100%;
      top: 100%;
      margin-left: 1px;
      margin-top: 1px;
    }
    // 覆盖
    &.ne,
    &.nw,
    &.se,
    &.sw {
      z-index: 6;
    }
    &.n b,
    &.s b {
      width: 14px;
      height: 7px;
    }
    &.e b,
    &.w b {
      height: 14px;
      width: 7px;
    }
    &.ne b,
    &.nw b,
    &.se b,
    &.sw b {
      width: 12px;
      height: 12px;
      border-radius: 50%;
    }
  }
  // 旋转按钮
  .editor-rotator {
    cursor: pointer;
    margin: 14px 0 0 -11px;
    position: absolute;
    left: 50%;
    top: 100%;
    z-index: 4;
    b {
      display: block;
      background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg width='24' height='24' xmlns='http://www.w3.org/2000/svg' fill='%23757575'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Ccircle stroke='%23CCD1DA' fill='%23FFF' cx='12' cy='12' r='11.5'/%3E%3Cpath d='M16.242 12.012a4.25 4.25 0 00-5.944-4.158L9.696 6.48a5.75 5.75 0 018.048 5.532h1.263l-2.01 3.002-2.008-3.002h1.253zm-8.484-.004a4.25 4.25 0 005.943 3.638l.6 1.375a5.75 5.75 0 01-8.046-5.013H5.023L7.02 9.004l1.997 3.004h-1.26z' fill='%23000' fill-rule='nonzero'/%3E%3C/g%3E%3C/svg%3E");
      width: 22px;
      height: 22px;
      background-size: 100% 100%;
      position: relative;
      z-index: 2;
      span {
        font-weight: 400;
        font-style: normal;
        position: absolute;
        top: 40px;
        left: 50%;
        transform: translateX(-50%);
        height: 28px;
        line-height: 28px;
        padding: 0 8px;
        font-size: 12px;
        background: #0e1217;
        border-radius: 4px;
        color: #fff;
      }
    }
  }
  // 旋转状态
  &.rotation_0 {
    .editor-rotator {
      cursor: url(../assets/svg/ic_mouse_rotation_0.svg) 11 9, pointer;
    }
  }
  &.rotation_45 {
    .editor-rotator {
      cursor: url(../assets/svg/ic_mouse_rotation_45.svg) 11 11, pointer;
    }
  }
  &.rotation_90 {
    .editor-rotator {
      cursor: url(../assets/svg/ic_mouse_rotation_90.svg) 9 11, pointer;
    }
  }
  &.rotation_135 {
    .editor-rotator {
      cursor: url(../assets/svg/ic_mouse_rotation_135.svg) 11 11, pointer;
    }
  }
  &.rotation_180 {
    .editor-rotator {
      cursor: url(../assets/svg/ic_mouse_rotation_180.svg) 11 13, pointer;
    }
  }
  &.rotation_-135 {
    .editor-rotator {
      cursor: url(../assets/svg/ic_mouse_rotation_-135.svg) 11 11, pointer;
    }
  }
  &.rotation_-90 {
    .editor-rotator {
      cursor: url(../assets/svg/ic_mouse_rotation_-90.svg) 13 11, pointer;
    }
  }
  &.rotation_-45 {
    .editor-rotator {
      cursor: url(../assets/svg/ic_mouse_rotation_-45.svg) 11 11, pointer;
    }
  }
  &.rotation_135,
  &.rotation_-45 {
    .editor-grip.n,
    .editor-grip.s {
      cursor: nwse-resize;
    }
    .editor-grip.w,
    .editor-grip.e {
      cursor: nesw-resize;
    }
    .editor-grip.nw,
    .editor-grip.se {
      cursor: ew-resize;
    }
    .editor-grip.ne,
    .editor-grip.sw {
      cursor: ns-resize;
    }
  }
  &.rotation_90,
  &.rotation_-90 {
    .editor-grip.n,
    .editor-grip.s {
      cursor: ew-resize;
    }
    .editor-grip.w,
    .editor-grip.e {
      cursor: ns-resize;
    }
    .editor-grip.nw,
    .editor-grip.se {
      cursor: nesw-resize;
    }
    .editor-grip.ne,
    .editor-grip.sw {
      cursor: nwse-resize;
    }
  }
  &.rotation_45,
  &.rotation_-135 {
    .editor-grip.n,
    .editor-grip.s {
      cursor: nesw-resize;
    }
    .editor-grip.w,
    .editor-grip.e {
      cursor: nwse-resize;
    }
    .editor-grip.nw,
    .editor-grip.se {
      cursor: ns-resize;
    }
    .editor-grip.ne,
    .editor-grip.sw {
      cursor: ew-resize;
    }
  }
  // 图片裁剪
  .clip-img {
    opacity: 0.3;
    cursor: default;
  }
  .element-image-img {
    position: relative;
    width: 100%;
    height: 100%;
    max-width: none;
    display: block;
  }
  .element-image-img-wrap {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    &.clip-mask {
      img {
        pointer-events: none;
      }
    }
    .editor-croper-grid {
      overflow: hidden;
      box-sizing: border-box;
      border: 1px solid #fff;
      height: 100%;
      width: 100%;
      position: absolute;
      left: 0;
      top: 0;
      i {
        display: none;
        border: 0 solid hsla(0,0%,100%,.7);
        position: absolute;
        left: 0;
        top: 0;
        &[class^=editor-croper-grid-x] {
          border-left-width: 1px;
          height: 100%;
          width: 0;
        }
        &[class^=editor-croper-grid-y] {
          border-top-width: 1px;
          width: 100%;
          height: 0;
        }
        &.editor-croper-grid-x1 {
          left: 33.3332%;
        }
        &.editor-croper-grid-x2 {
          left: 66.6664%;
        }
        &.editor-croper-grid-y1 {
          top: 33.332%;
        }
        &.editor-croper-grid-y2 {
          top: 66.664%;
        }
      }
      &.editor-mask-editor-grid {
        border: 1px solid transparent;
        position: absolute;
        z-index: 2;
        pointer-events: none;
      }
      &.active {
        i {
          display: block;
        }
      }
    }
  }
  .toolbar-wrap {
    position: absolute;
    left: 50%;
    top: 50%;
    height: 38px;
    background: #fff;
    border-radius: 3px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    font-size: 14px;
    padding: 5px;
    cursor: default;
    display: flex;
    align-items: center;
    z-index: 6;
    span {
      padding: 0 5px;
      white-space: nowrap;
    }
    button {
      background: none;
      border: 0;
      border-radius: 3px;
      cursor: pointer;
      line-height: 1;
      outline: 0;
      position: relative;
      white-space: nowrap;
      vertical-align: top;
      text-align: center;
      width: 40px;
      height: 28px;
      font-size: 12px;
    }
    .el-slider {
      width: 160px;
      margin: 0 10px;
    }
    .toolbar-pipe {
      border-left: 1px solid #eee;
      margin: 0 1px;
      height: 16px;
      width: 0;
      &.is-full {
        height: 38px;
      }
    }
  }
}
</style>
