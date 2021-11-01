<template>
  <div :class="{'aside-nav-com': true, collapse: isCollapse}">
    <el-tabs tab-position="left" v-model="asideTabVal">
      <el-tab-pane label="模板" name="templates">
        <el-tabs v-model="templateTabVal">
          <el-tab-pane label="模板中心" name="template-center">
            <el-radio-group size="mini">
              <el-radio-button label="标签1">标签1</el-radio-button>
              <el-radio-button label="标签2">标签2</el-radio-button>
            </el-radio-group>
            <el-divider></el-divider>
            <el-card shadow="hover" v-for="(v, i) in templates" :key="v.id" @click.native="$bus.$emit('selTpl', v)">
              <h5>{{i + 1}}、模板{{i + 1}}</h5>
              <el-image :src="v.thumb" fit="cover"></el-image>
            </el-card>
          </el-tab-pane>
          <el-tab-pane label="我的模板" name="my-template">我的模板</el-tab-pane>
        </el-tabs>
      </el-tab-pane>
      <el-tab-pane label="文字" name="texts">
        <h4>文字模板</h4>
        <el-tabs v-model="textTabVal">
          <el-tab-pane label="标题" name="title">
            <el-card shadow="hover" v-for="(v, i) in defTextElements" :key="i">
              <h5>{{i + 1}}、</h5>
              <div class="relative">
                <div :class="{ grab: true, grabbing: dragElUUID === v.uuid }"
                  :style="$parent.getPxStyle(v.style, 1, defTitleCoverStyle)"
                  @mousedown="handleDragStartFromMixin($event, v)"
                >{{v.value}}</div>
              </div>
            </el-card>
          </el-tab-pane>
          <el-tab-pane label="正文" name="text">正文</el-tab-pane>
        </el-tabs>
      </el-tab-pane>
      <el-tab-pane label="元素" name="elements">
        <h4>元素模板</h4>
        <el-row :gutter="10" class="elements-row">
          <el-col :span="12" v-for="v in defImgElements" :key="v.uuid">
            <el-card shadow="hover">
              <img :class="{ grab: true, grabbing: dragElUUID === v.uuid }"
                :style="$parent.getPxStyle(v.style, 1, defImgCoverStyle)"
                @mousedown="handleDragStartFromMixin($event, v)"
                :src="v.value" alt="">
            </el-card>
          </el-col>
        </el-row>
      </el-tab-pane>
      <el-tab-pane label="背景" name="backgrounds">
        <h4>背景模板</h4>
      </el-tab-pane>
      <el-tab-pane label="组件" name="components">
        <h4>组件库</h4>
      </el-tab-pane>
      <el-tab-pane label="素材" name="materials">
        <el-tabs v-model="materialTabVal">
          <el-tab-pane label="我的素材" name="my-material">我的素材</el-tab-pane>
          <el-tab-pane label="款式库" name="style-library">款式库</el-tab-pane>
        </el-tabs>
      </el-tab-pane>
    </el-tabs>
    <div class="append-pop" @click="setIsCollapse(!isCollapse)"></div>
  </div>
</template>

<script>
import DragMixin from '@/utils/drag-mixin'
import { mapGetters, mapMutations, mapActions } from 'vuex';

import defStyle from '@/common/elementModel';
const { eleDefCommonStyle, pageCoverStyle, titleCoverStyle, infoCoverStyle, gridCoverStyle } = defStyle;

export default {
  name: "AsideNavCom",
  mixins: [DragMixin],
  data() {
    return {
      // tab默认值
      asideTabVal: 'elements',
      templateTabVal: 'template-center',
      textTabVal: 'title',
      materialTabVal: 'my-material',
      // 拖拽中元素的uuid
      dragElUUID: '',
      // 覆盖样式 标题列表
      defTitleCoverStyle: {
        position: 'initial'
      },
      // 覆盖样式 元素列表
      defImgCoverStyle: {
        position: 'initial',
        width: 100,
        height: 'auto'
      },
      // 标题列表
      defTextElements: [
        {
          uuid: this.$createUUID(),
          type: 'text',
          value: '子标题1',
          valueType: 'String',
          style: {
            ...eleDefCommonStyle,
            display: 'inline-block',
            position: 'absolute',
            left: 40,
            top: 40,
            height: 30,
            lineHeight: 30,
            paddingLeft: 10,
            paddingRight: 10,
            borderRadius: 4,
            color: 'rgba(255, 255, 255, 1)',
            backgroundColor: 'rgba(255, 69, 0, 1)'
          }
        },
        {
          uuid: this.$createUUID(),
          type: 'text',
          value: '子标题2',
          valueType: 'String',
          style: {
            ...eleDefCommonStyle,
            display: 'inline-block',
            position: 'absolute',
            left: 40,
            top: 40,
            height: 40,
            lineHeight: 40,
            paddingLeft: 20,
            paddingRight: 20,
            borderRadius: 10,
            boxShadow: '0 0 5px #333',
            color: 'rgba(255, 255, 255, 1)',
            backgroundColor: 'rgba(255, 165, 0, 1)'
          }
        },
      ],
      // 元素列表
      defImgElements: [
        {
          uuid: this.$createUUID(),
          type: 'image',
          value: 'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg',
          valueType: 'String',
          style: {
            ...eleDefCommonStyle,
            position: 'absolute',
            left: 40,
            top: 40,
            width: 300,
            height: 200,
            lineHeight: 1,
          }
        },
        {
          uuid: this.$createUUID(),
          type: 'image',
          value: 'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg',
          valueType: 'String',
          style: {
            ...eleDefCommonStyle,
            position: 'absolute',
            left: 40,
            top: 40,
            width: 300,
            height: 200,
            lineHeight: 1,
          }
        },
      ],
    }
  },
  computed: {
    ...mapGetters(['isCollapse', 'templates', 'scale']),
  },
  mounted() {
    this.getTemplates().then(res => {
      console.log(res)
      this.$message.success("获取模板列表成功");
    }).catch(() => {
      this.$message.error("获取模板列表失败");
    });
  },
  methods: {
    ...mapMutations(['setIsCollapse', 'setReportItemData']),
    ...mapActions(['getTemplates'])
  }
};
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';

.aside-nav-com {
  position: absolute;
  top: $header-height;
  bottom: 0;
  left: 0;
  width: 68px;
  box-shadow: $box-shadow;
  background: #fff;
  z-index: 7;
  transition: width 0.3s ease;
  &.collapse {
    width: $aside-width;
  }
  h4 {
    font-size: 14px;
    padding: 5px;
    margin-bottom: 10px;
    color: #fff;
    background-color: #409EFF;
  }
  .elements-row {
    .el-col {
      margin-bottom: 10px;
      .grab {
        img {
          width: 100%;
        }
      }
    }
  }
  .el-tabs {
    height: 100%;
  }
  .el-card {
    cursor: pointer;
    & + .el-card {
      margin-top: 10px;
    }
    h5 {
      margin-bottom: 10px;
    }
    .relative {
      position: relative;
    }
  }
  .append-pop {
    position: absolute;
    left: 100%;
    top: 50%;
    z-index: 2;
    transform: translateY(-50%);
    margin-left: -1px;
    width: 20px;
    height: 64px;
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAACACAMAAABOb9vcAAAAhFBMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8AAADHx8cODg50dHTx8fF2dnZ1dXWWlpZHR0c4ODhQpkZ5AAAAIXRSTlMA9t+/upkRAnPq5NXDfDEsKQjMeGlRThkMsquljTwzIWhBHpjgAAABJElEQVRYw+3YyW7CQBCEYbxig8ELGJyQkJRJyPb+75dj3zy/lD7kMH3+ZEuzSFO1mlZwhjOE2uwhVHJYMygNVwilhz2EUvNaMigledUFoE1anKYAtA9nVRuANpviOQBt0t2ZQSnZ9QxK6Qih9LSGUHkJobYlhGp6CPW4hlAVhckLhMop1InCjEK1FBYU1hSqo/BI4YXCjMIthTWFijDCCB3g7fuO4O1t/rkvQXPz/LUIzX0oAM0tQHOfCkBzC9DcuwLQXACao9Dv1yb9lsek2xaaxMcMH1x6Ff79dY0wwgj/DGv3p2tG4cX9wd55h4rCO/hk3uEs9w6QlXPIbXrfIJ6XrmVBOtJCA1YkXqVLkh1aUgyNk1fV1BxLxzpsuNLKzrME/AWr0ywwvyj83AAAAABJRU5ErkJggg==);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: 50%;
    cursor: pointer;
  }
}

.grab {
  user-select: none;
  cursor: grab;
  touch-action: manipulation;
}
.grabbing {
  cursor: grabbing;
  pointer-events: none;
  z-index: 9 !important;
}
</style>
<style lang="scss">
.aside-nav-com {
  & > .el-tabs {
    &.el-tabs--left .el-tabs__header.is-left {
      margin-right: 0;
    }
    & > .el-tabs__content {
      width: 233px;
      height: 100%;
      padding: 10px;
      box-sizing: border-box;
      overflow: auto;
      .el-divider--horizontal {
        margin: 10px 0;
      }
    }
  }
  .elements-row {
    .el-card .el-card__body {
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 0;
    }
  }
}

.cursor-grabbing * {
  cursor: grabbing !important;
}
</style>
