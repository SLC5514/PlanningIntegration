<script lang="ts" setup>
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup
import { computed, watch } from 'vue'
import { useStore } from 'vuex'
import { ElLoading } from 'element-plus'
import zhCn from 'element-plus/lib/locale/lang/zh-cn'

const store = useStore()
let loading = computed(() => store.state.app.loading)
let fullscreenLoading: any

const openFullScreen = () => {
  fullscreenLoading = ElLoading.service({
    lock: true,
    // text: '拼命加载中',
    // spinner: 'el-icon-loading',
    // background: 'rgba(0, 0, 0, 0.7)',
  })
}
const closeFullScreen = () => {
  fullscreenLoading && fullscreenLoading.close()
}
const watchLoading = (val: boolean) => {
  if (val) {
    openFullScreen()
  } else {
    closeFullScreen()
  }
}
watchLoading(loading.value)
watch(loading, (val, preVal) => {
  watchLoading(val)
})

</script>

<template>
  <el-config-provider :locale="zhCn">
    <div id="root">
      <router-view />
    </div>
  </el-config-provider>
</template>

<style lang="scss">
#root {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  height: 100%;
}
</style>
