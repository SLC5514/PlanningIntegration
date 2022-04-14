<template>
  <div class="container">
    <Header />
    <Footer />
    <AsideNav />
    <ControllerNav />
    <!-- 新增子报告 -->
    <AddSubReportDialog />
    <!-- 主画布 -->
    <div :class="{'paper-main': true, 'no-collapse': !isCollapse}" ref="paper-main" @mousedown.left.stop="clickWrap">
      <div class="paper-wrap-box" v-if="reportData.length">
        <div class="paper-wrap" :style="paperWrapStyle" @mousedown.stop="() => { return false }">
          <ToolNav :style="`transform: scale(${(1 - defScale) + 1});`" />
          <div :class="{'paper-page': true, on: activeElementUUID === v.uuid}" v-for="(v, i) in reportData" :key="v.uuid"
            :style="getPxStyle(v.style)"
            @mousedown.left.stop="clickPage(v.uuid, v.type, i)"
            @mouseenter="hoverReportIdx = i"
            @mouseleave="hoverReportIdx = -1">
            <EditShape :class="k.type !== 'grid-box' ? 'element element-' + k.type : ''" v-for="(k, idx) in v.elements" :key="k.uuid"
              :item="k"
              :disable="k.type === 'grid-box'"
              :page-idx="i"
              :el-idx="idx"
              :refer-elements="referElements"
            >
              <template v-if="k.type === 'grid-box'" slot="grid-box">
                <EditShape :class="'element element-' + item.type" v-for="(item, index) in k.grids" :key="item.uuid"
                  :item="item"
                  :disable="item.type === 'grid-box'"
                  :page-idx="i"
                  :el-idx="idx"
                  :grid-idx="index"
                  :refer-elements="referElements"
                ></EditShape>
              </template>
            </EditShape>
            <div class="ref-v-line" v-for="v in vLines" :key="v.left + '-' + Math.random()" :style="{ left: v.left + 'px' }"></div>
            <div class="ref-h-line" v-for="v in hLines" :key="v.top + '-' + Math.random()" :style="{ top: v.top + 'px' }"></div>
          </div>
        </div>
      </div>
      <div v-else class="empty-info-box" @click.stop="() => { return false }">
        <el-image src="https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg" fit="cover"></el-image>
        <el-button @click="$bus.$emit('showAddSubReportDialog')">新增子报告</el-button>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import hotkeys from 'hotkeys-js';
import { mapGetters, mapMutations } from 'vuex';
import defStyle from '@/common/elementModel';

const { eleDefCommonStyle, pageCoverStyle, titleCoverStyle, infoCoverStyle, gridCoverStyle } = defStyle;

export default {
  components: {
    Header: () => import("@/components/Header"),
    Footer: () => import("@/components/Footer"),
    AsideNav: () => import("@/components/AsideNav"),
    ControllerNav: () => import("@/components/ControllerNav"),
    ToolNav: () => import("@/components/ToolNav"),
    AddSubReportDialog: () => import("@/components/AddSubReportDialog"),
    EditShape: () => import("@/components/EditShape"),
  },
  data() {
    return {
      reportId: this.$route.query.id, // 主报告id
      // 画布数据
      paperParent: {
        width: 0,
        height: 0,
      },
      paper: {
        width: 1200,
        height: 820,
        scale: 1,
        marginSub: 80
      },
      // 参考线位置
      vLines: [],
      hLines: [],
      // 所有元素
      referElements: [],
      // 鼠标划过子报告下标
      hoverReportIdx: -1,
      // 同步侧栏收起动效时间
      collapseDuration: 300,
      // 需要监听的键
      watchHotkeys: [
        'ctrl+z', // 撤回
        'ctrl+x', // 前进
        'ctrl+shift+s', // 另存
        'ctrl+s', // 保存
        'f5', // 预览
        'delete', // 删除
        'ctrl+c', // 复制
        'ctrl+v', // 粘贴
        // 'ctrl+g', // 组合
        // 'ctrl+shift+g', // 解除组合
      ],
    };
  },
  computed: {
    ...mapGetters(['isCollapse', 'scale', 'isResize', 'gridScale', 'reportIdx', 'reportData', 'activeElementUUID', 'isHotShift', 'isClipImg']),
    defScale() {
      return this.scale / 100;
    },
    // 画布样式
    paperWrapStyle() {
      let isStW = false,
        isStH = false;
      const scale = this.defScale;
      const differenceW = this.paperParent.width - (this.paper.width * scale + this.paper.marginSub);
      const differenceH = this.paperParent.height - (this.paper.height * this.reportData.length * scale + this.paper.marginSub);
      if (this.paper.width * scale + this.paper.marginSub < this.paperParent.width) {
        isStW = true;
      }
      if (this.paper.height * this.reportData.length * scale + this.paper.marginSub < this.paperParent.height) {
        isStH = true;
      }
      return {
        'width': this.paper.width + "px",
        'height': this.paper.height * this.reportData.length + "px",
        'margin-left': this.paper.marginSub / 2 + (differenceW > 0 ? differenceW / 2 : 0) + "px",
        'margin-right': this.paper.marginSub / 2 + (scale > 1 ? this.paper.width * (scale - 1) : scale < 1 ? -this.paper.width * (1 - scale) : 0) + "px",
        'margin-top': this.paper.marginSub / 2 + (differenceH > 0 ? differenceH / 2 : 0) + "px",
        'margin-bottom': this.paper.marginSub / 2 + (scale > 1 ? this.paper.height * this.reportData.length * (scale - 1) : scale < 1 ? -this.paper.height * this.reportData.length * (1 - scale) : 0) + "px",
        'transform-origin': '0 0',
        'transform': `scale(${scale})`,
      }
    },
  },
  watch: {
    // 侧栏展开收起 合适画布
    isCollapse() {
      const timeout = setTimeout(() => {
        this.isResizeChange(true);
        this.windowResize();
        clearTimeout(timeout);
      }, this.collapseDuration);
    }
  },
  mounted() {
    // 元素编辑换行指定元素
    document.execCommand("defaultParagraphSeparator", false, "p");

    this.windowResize();
    window && window.addEventListener('resize', this.windowResize);
    this.$bus.$on('autoSize', this.windowResize);
    this.$bus.$on('selTpl', this.selTpl);
    // 参考线相关
    this.$bus.$on('drawVLine', this.drawVLine);
    this.$bus.$on('clearVLine', this.clearVLine);
    this.$bus.$on('drawHLine', this.drawHLine);
    this.$bus.$on('clearHLine', this.clearHLine);
    this.$bus.$on('initReferElements', this.initReferElements);
    // 快捷键
    hotkeys('*', { keyup: true }, (e, handler) => {
      if (hotkeys.shift) {
        if (e.type === 'keydown' && !this.isHotShift) {
          this.setIsHotShift(true);
        }
        if (e.type === 'keyup' && this.isHotShift) {
          this.setIsHotShift(false);
        }
      }
    });
    hotkeys(this.watchHotkeys.join(), (e, handler) => {
      e.preventDefault();
      switch (handler.key) {
        case this.watchHotkeys[0]: // 撤回
          console.log('撤回');
          break;
        case this.watchHotkeys[1]: // 前进
          console.log('前进');
          break;
        case this.watchHotkeys[2]: // 另存
          console.log('另存');
          break;
        case this.watchHotkeys[3]: // 保存
          console.log('保存');
          break;
        case this.watchHotkeys[4]: // 预览
          console.log('预览');
          break;
        case this.watchHotkeys[5]: // 删除
          console.log('删除');
          break;
        case this.watchHotkeys[6]: // 复制
          console.log('复制');
          break;
        case this.watchHotkeys[7]: // 粘贴
          console.log('粘贴');
          break;
        default: ;
      }
    });
  },
  methods: {
    ...mapMutations(['scaleChange', 'setReportIdx', 'setReportItemData', 'setActiveElementUUID', 'isResizeChange', 'setIsHotShift', 'setIsClipImg']),
    // 重置视口
    windowResize() {
      this.$refs['paper-main'] && (this.$refs['paper-main'].style = 'overflow: hidden;');
      this.paperParent.width = this.$refs['paper-main'] && this.$refs['paper-main'].clientWidth;
      this.paperParent.height = this.$refs['paper-main'] && this.$refs['paper-main'].clientHeight;
      let rw = (this.paperParent.width - this.paper.marginSub) / this.paper.width,
        rh = (this.paperParent.height - this.paper.marginSub) / this.paper.height;
      if (this.isResize) {
        if (rw < rh) {
          this.paper.scale = rw;
        } else {
          this.paper.scale = rh
        }
      }
      if (this.paper.scale < 0.5) {
        this.paper.scale = 0.5;
      } else if (this.paper.scale > 2) {
        this.paper.scale = 2;
      }
      // 设置合适缩放值
      this.scaleChange(this.paper.scale * 100);
      this.$nextTick(() => {
        this.$refs['paper-main'] && (this.$refs['paper-main'].style = 'overflow: auto;');
      })
    },
    // 选择模板
    selTpl(item) {
      if (!this.reportData[this.reportIdx]) {
        this.$message.warning('请添加报告后，再选择模板');
        return;
      }
      // 格式化格子数据
      let grids = this.formatGrids(item.grids);
      // 获取报告elements中格子的下标
      const eleGridIdx = this.getEleGridIdx();
      // 操作
      if (eleGridIdx > -1 && this.reportData[this.reportIdx].elements[eleGridIdx]) { // 修改
        this.setReportItemData({ keys: [this.reportIdx, 'elements', eleGridIdx], key: 'id', val: item.id });
        this.setReportItemData({ keys: [this.reportIdx, 'elements', eleGridIdx], key: 'grids', val: grids });
      } else { // 新增
        this.setReportItemData({ keys: [this.reportIdx, 'elements'], val: {
          id: item.id,
          type: 'grid-box',
          grids: grids
        } });
      }
    },
    // 获取格子元素位置
    getEleGridIdx(reportIdx) {
      const idx = reportIdx !== undefined ? reportIdx : this.reportIdx;
      let eleGridIdx = -1;
      if (idx < 0) {
        return eleGridIdx;
      }
      if (this.reportData[idx].elements) {
        for (let i = 0; i < this.reportData[idx].elements.length; i++) {
          if (this.reportData[idx].elements[i].type === 'grid-box') {
            eleGridIdx = i;
            break;
          }
        }
      }
      return eleGridIdx;
    },
    // 格式化格子数据
    formatGrids(ogGrids) {
      let grids = [];
      for (let i = 0; i < ogGrids.length; i++) {
        grids.push({
          uuid: this.$createUUID(),
          type: 'grid',
          style: {
            ...gridCoverStyle,
            ...ogGrids[i],
            left: ogGrids[i].x + 40 / this.gridScale,
            top: ogGrids[i].y + 150 / this.gridScale
          },
          clip: {
            w: 0,
            h: 0,
            x: 0,
            y: 0,
            scale: 1,
          }
        });
      }
      return grids;
    },
    // 处理样式
    getPxStyle(paramStyle, scale = 1, coverStyle = {}) {
      let style = {};
      const needUnitStr = ['width', 'height', 'top', 'left', 'paddingTop', 'paddingLeft', 'paddingRight', 'paddingBottom', 'marginTop', 'marginLeft', 'marginRight', 'marginBottom', 'borderWidth', 'fontSize', 'borderRadius', 'letterSpacing'];
      const noNeedUnitStr = ['x', 'y'];
      let itemCoverStatus = false;
      for (let key in paramStyle) {
        itemCoverStatus = coverStyle[key] !== undefined;
        if (needUnitStr.includes(key) || (key === 'lineHeight' && (itemCoverStatus ? coverStyle[key] : paramStyle[key]) > 2)) { // lineHeight过大处理为添加px
          if ((key === 'width' || key === 'height') && (itemCoverStatus ? coverStyle[key] : paramStyle[key]) === 'auto') { // 属性兼容
            style[key] = itemCoverStatus ? coverStyle[key] : paramStyle[key];
          } else {
            style[key] = (itemCoverStatus ? coverStyle[key] : paramStyle[key]) * scale + 'px';
          }
        } else if (noNeedUnitStr.includes(key)) {
          continue;
        } else if (key === 'rotate') {
          style.transform = `rotate(${itemCoverStatus ? coverStyle[key] : paramStyle[key]}deg)`;
        } else if (key === 'backgroundImage') {
          style.backgroundImage = `url(${itemCoverStatus ? coverStyle[key] : paramStyle[key]})`;
        } else {
          style[key] = itemCoverStatus ? coverStyle[key] : paramStyle[key];
        }
      }
      return style;
    },
    // 点击空白
    clickWrap() {
      if (this.isClipImg) {
        this.$bus.$emit('clipSliderSuc');
        return;
      }
      this.setActiveElementUUID('');
      this.$bus.$emit('setColNavType');
    },
    // 点击页面
    clickPage(uuid, type, idx) {
      if (this.isClipImg) {
        this.$bus.$emit('clipSliderSuc');
        return;
      }
      this.setReportIdx(idx);
      this.setActiveElementUUID(uuid);
      this.$bus.$emit('setColNavType', type);
    },
    // 初始化[所有元素]变量
    initReferElements(uuid) {
      this.referElements = [];
      for (let i = 0; i < this.reportData.length; i++) {
        this.referElements.push(this.reportData[i]);
        for (let j = 0; j < this.reportData[i].elements.length; j++) {
          if (this.reportData[i].elements[j].type === 'grid-box') { // 格子父元素
            for (let k = 0; k < this.reportData[i].elements[j].grids.length; k++) {
              if (this.reportData[i].elements[j].grids[k].uuid !== uuid) {
                this.referElements.push(this.reportData[i].elements[j].grids[k]);
              }
            }
          } else if (this.reportData[i].elements[j].uuid !== uuid) { // 普通元素
            this.referElements.push(this.reportData[i].elements[j]);
          }
        }
      }
    },
    // 生成垂直参考线
    drawVLine(newLeft) {
      this.vLines = [{ left: newLeft }];
    },
    // 清空垂直参考线
    clearVLine() {
      this.vLines = [];
    },
    // 生成水平参考线
    drawHLine(newTop) {
      this.hLines = [{ top: newTop }];
    },
    // 清空水平参考线
    clearHLine() {
      this.hLines = [];
    },
  }
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/variables.scss";

.container {
  position: relative;
  height: 100vh;
  overflow: hidden;
  .paper-main {
    position: absolute;
    left: $aside-width;
    right: $aside-width;
    top: $header-height;
    bottom: $footer-height;
    font-size: 0;
    background: #eee;
    overflow: auto;
    transition: left 0.3s ease;
    &.no-collapse {
      left: $aside-nocollapse-width;
    }
    .paper-wrap-box {
      position: relative;
      display: inline-block;
      min-width: 100%;
      min-height: 100%;
      overflow: hidden;
      .paper-wrap {
        display: inline-block;
        font-size: 14px;
        user-select: none;
        box-shadow: 1px 1px 15px rgba(0,0,0,.2);
        background-color: #fff;
        background-size: 16px 16px;
        background-position: 0 0,8px 8px;
        background-image: linear-gradient(to top right,#ccc 25%,transparent 0,transparent 75%,#ccc 0,#ccc),linear-gradient(to top right,#ccc 25%,transparent 0,transparent 75%,#ccc 0,#ccc);
        .paper-page {
          position: relative;
          width: 1200px;
          height: 820px;
          &:hover, &.on {
            z-index: 1000;
            outline: 1px solid #409EFF;
          }
          &:hover {
            z-index: 1001;
          }
          .element {
            display: inline-block;
          }
          .element-image img {
            width: 100%;
            height: 100%;
          }
        }
        .ref-v-line {
          position: absolute;
          top: 0;
          bottom: 0;
          width: 1px;
          height: 100%;
          z-index: 1001;
          background-color: #FF4AFF;
          pointer-events: none;
        }
        .ref-h-line {
          position: absolute;
          left: 0;
          right: 0;
          width: 100%;
          height: 1px;
          z-index: 1001;
          background-color: #FF4AFF;
          pointer-events: none;
        }
      }
    }
    .empty-info-box {
      text-align: center;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      .el-image {
        display: block;
        margin: 0 auto;
        width: 300px;
        margin-bottom: 20px;
      }
    }
  }
}
</style>
