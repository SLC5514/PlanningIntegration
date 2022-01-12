<script lang="ts" setup>
import { useAppStore } from '~/stores/app'

const appStore = useAppStore()
const loading = ref(true)

setTimeout(function() {
  loading.value = false
}, 3000)
</script>

<template>
  <el-header id="header">
    <div>
      <el-button>返回列表</el-button>
    </div>
    <div>
      <el-skeleton :loading="loading" animated>
        <template #template>
          <el-skeleton-item variant="rect" class="w-60px h-32px align-middle" />
          <el-skeleton-item variant="rect" class="w-60px h-32px align-middle" />
          <el-skeleton-item variant="rect" class="w-60px h-32px align-middle" />
          <el-skeleton-item variant="rect" class="w-60px h-32px align-middle" />
          <el-skeleton-item variant="rect" class="w-60px h-32px align-middle" />
          <el-skeleton-item variant="rect" class="w-60px h-32px align-middle" />
          <el-skeleton-item variant="rect" class="w-60px h-32px align-middle" />
          <el-skeleton-item variant="rect" class="w-60px h-32px align-middle" />
          <el-skeleton-item variant="rect" class="w-60px h-32px align-middle" />
        </template>
        <template #default>
          <el-button>撤销</el-button>
          <el-button>前进</el-button>
          <el-button>文字</el-button>
          <el-button>图片</el-button>
          <el-button>下载</el-button>
          <el-button>复制</el-button>
          <el-button>另存为</el-button>
          <el-button>预览</el-button>
          <el-button>保存</el-button>
        </template>
      </el-skeleton>
    </div>
    <div>
      <el-button>操作说明</el-button>
      <el-button>模板设置</el-button>
      <el-select v-model="appStore.locale">
        <el-option
          v-for="(item, index) in appStore.localeList"
          :key="index"
          :value="item.value"
          :label="item.name"
        />
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
  .el-skeleton .el-skeleton__item + .el-skeleton__item {
    margin-left: 12px;
  }
}
</style>
