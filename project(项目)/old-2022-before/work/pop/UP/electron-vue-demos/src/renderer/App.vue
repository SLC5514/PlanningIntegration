<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'electron-vue-demos'
}
</script>

<style lang="less">
#app {
  font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB",
    "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
  .index-page {
    .el-tabs--left .el-tabs__header.is-left{
      margin-right: 20px;
    }
  }
}
</style>
