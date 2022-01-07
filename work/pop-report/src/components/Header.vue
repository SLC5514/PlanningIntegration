<script lang="ts" setup>
import { useAppStore } from '~/stores/app'

const appStore = useAppStore()
</script>

<template>
  <el-header id="header">
    <div>
      <el-button>返回列表</el-button>
    </div>
    <div>
      <el-button>撤销</el-button>
      <el-button>前进</el-button>
      <el-button>文字</el-button>
      <el-button>图片</el-button>
      <el-button>下载</el-button>
      <el-button>复制</el-button>
      <el-button>另存为</el-button>
      <el-button>预览</el-button>
      <el-button>保存</el-button>
    </div>
    <div>
      <el-button>操作说明</el-button>
      <el-button>模板设置</el-button>
      <el-select v-model="appStore.locale">
        <el-option value="zh-CN">中文</el-option>
        <el-option value="en">English</el-option>
      </el-select>
    </div>
  </el-header>
</template>

<style lang="scss" scoped>
#header {
  height: 55px;
  line-height: 55px;
  padding: 0 10px;
  background: #fff;
  border-bottom: 1px solid #ececec;
  position: relative;
  z-index: 9;
  display: flex;
  justify-content: space-between;
  .el-button + .el-select {
    width: 100px;
    margin-left: 12px;
  }
}
</style>
