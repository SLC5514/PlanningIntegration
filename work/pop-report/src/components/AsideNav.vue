<script lang="ts" setup>
// import { useUserStore } from '~/stores/user';
// console.log(useUserStore())

// import { getPageReport } from "~/api/report"

// getPageReport({
//   site: 1,
//   main_id: 12188,
//   hash: '04b6c894c73c9a363e1fb69a39342f40-2143-16415234509',
// }).then(res => {
//   console.log(res)
// }).catch(err => {
//   console.log(err)
// })

const isCollapse = ref(false)
const initTab = ref({}) // tab初始状态
const tabType = ref('') // 一级tab
const tabTypeSub = reactive({
  template: '', // 模板
  text: '', // 文字
  image: '', // 元素
  background: '', // 背景
  component: '', // 组件
  material: '', // 素材
}) // 二级tab

// 一二级监听初始
watch([tabType, tabTypeSub], ([tabTypeVal, tabTypeSubVal]) => {
  if (tabTypeVal && !initTab.value[tabTypeVal]) {
    initTab.value[tabTypeVal] = true
    console.log(tabTypeVal, '一级初始')
    tabTypeSub[tabTypeVal] = tabTypeVal + '-0'
  }
  for (const i in tabTypeSubVal) {
    if (tabTypeSubVal[i] && !initTab.value[tabTypeSubVal[i]]) {
      initTab.value[tabTypeSubVal[i]] = true
      console.log(tabTypeSubVal[i], '二级初始')
    }
  }
})

// init
tabType.value = 'template'
</script>

<template>
  <el-aside id="aside-nav" :class="{ collapse: isCollapse }">
    <el-tabs tab-position="left" v-model="tabType">
      <el-tab-pane label="模板" name="template">
        <el-tabs v-model="tabTypeSub.template">
          <el-tab-pane label="模板中心" name="template-0">模板中心</el-tab-pane>
          <el-tab-pane label="我的模板" name="template-1">我的模板</el-tab-pane>
        </el-tabs>
      </el-tab-pane>
      <el-tab-pane label="文字" name="text">
        <el-tabs v-model="tabTypeSub.text">
          <el-tab-pane label="标题模板" name="text-0">标题模板</el-tab-pane>
        </el-tabs>
      </el-tab-pane>
      <el-tab-pane label="元素" name="image">
        <el-tabs v-model="tabTypeSub.image">
          <el-tab-pane label="元素模板" name="image-0">元素模板</el-tab-pane>
        </el-tabs>
      </el-tab-pane>
      <el-tab-pane label="背景" name="background">
        <el-tabs v-model="tabTypeSub.background">
          <el-tab-pane label="背景模板" name="background-0">背景模板</el-tab-pane>
        </el-tabs>
      </el-tab-pane>
      <el-tab-pane label="组件" name="component">
        <el-tabs v-model="tabTypeSub.component">
          <el-tab-pane label="组件模板" name="component-0">组件模板</el-tab-pane>
        </el-tabs>
      </el-tab-pane>
      <el-tab-pane label="素材" name="material">
        <el-tabs v-model="tabTypeSub.material">
          <el-tab-pane label="我的素材" name="material-0">我的素材</el-tab-pane>
          <el-tab-pane label="款式库" name="material-1">款式库</el-tab-pane>
        </el-tabs>
      </el-tab-pane>
    </el-tabs>
    <i-mdi-light-chevron-left class="append-pop" @click="isCollapse = !isCollapse" />
  </el-aside>
</template>

<style lang="scss" scoped>
#aside-nav {
  width: 362px;
  box-shadow: 0 0 2px 2px hsl(0deg 0% 93% / 50%);
  background: #fff;
  position: relative;
  z-index: 8;
  transition: width 0.3s;
  display: flex;
  overflow: initial;
  &.collapse {
    width: 66px;
    .append-pop > :deep(path) {
      transform-origin: center;
      transform: rotate(180deg);
    }
  }
  & > .el-tabs {
    width: 100%;
    display: flex;
    :deep(.el-tabs__header) {
      width: 66px;
      min-width: 66px;
      margin-right: 0;
      font-size: 14px;
      text-align: center;
      .el-tabs__item {
        padding: 0;
        text-align: center;
      }
      .el-tabs__nav-wrap::after {
        height: 1px;
        background-color: #ececec;
      }
      .el-tabs__nav-wrap.is-left::after {
        height: 100%;
        width: 1px;
      }
      .el-tabs__active-bar.is-left {
        left: 0;
      }
    }
    :deep(.el-tabs__content) {
      min-width: 296px;
      height: 100%;
      margin-right: 0;
      .el-tabs__header {
        width: 100%;
        margin-bottom: 0;
        padding-top: 0;
        .el-tabs__nav {
          left: 50%;
          transform: translateX(-50%) !important;
        }
        .el-tabs__item + .el-tabs__item {
          margin-left: 20px;
        }
      }
      .el-tabs__content .el-tab-pane {
        height: calc(100vh - 55px - 40px);
        overflow: auto;
      }
    }
  }
  .append-pop {
    position: absolute;
    top: 50%;
    right: -20px;
    width: 20px;
    height: 40px;
    transform: translateY(-50%);
    background-color: #fff;
    border-top-right-radius: 15px;
    border-bottom-right-radius: 15px;
    box-shadow: 3px 0 2px 2px rgb(237 237 237 / 50%);
    cursor: pointer;
  }
}
</style>
