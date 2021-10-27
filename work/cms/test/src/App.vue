<script setup lang="ts">
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup
import HelloWorld from './components/HelloWorld.vue'
import { ref } from 'vue'
import { ElLoading } from 'element-plus'

const fullscreenLoading = ref(false)
const openFullScreen1 = () => {
  fullscreenLoading.value = true
  setTimeout(() => {
    fullscreenLoading.value = false
  }, 2000)
}

const openFullScreen2 = () => {
  const loading = ElLoading.service({
    lock: true,
    text: 'Loading',
    spinner: 'el-icon-loading',
    background: 'rgba(0, 0, 0, 0.7)',
  })
  setTimeout(() => {
    loading.close()
  }, 2000)
}
</script>

<template>
  <!-- <el-button type="primary" v-loading.fullscreen.lock="fullscreenLoading" @click="openFullScreen1">全屏加载1</el-button> -->
  <el-button type="primary" @click="openFullScreen2">全屏加载1</el-button>
  <img alt="Vue logo" src="./assets/logo.png" />
  <HelloWorld msg="Hello Vue 3 + TypeScript + Vite" />
</template>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
