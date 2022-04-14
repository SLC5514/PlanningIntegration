<script setup lang="ts">
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup

// import { onMounted } from "vue"
// import { usePrimeVue } from "primevue/config"

// import HelloWorld from './components/HelloWorld.vue'

// const primevue: any = usePrimeVue()
// const locale: any = primevue.config.locale

// onMounted(() => {
//   locale.accept = '同意'
// })
</script>

<template>
  <!-- <img alt="Vue logo" src="./assets/logo.png" />
  <HelloWorld msg="Hello Vue 3 + TypeScript + Vite" />
  <p-button :label="locale.accept" /> -->

  <router-view></router-view>
</template>

<style lang="scss">
#app {
  font-size: 16px;
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  background-color: $app-bgcolor;
  color: $app-color;
  min-height: 100vh;
}
</style>
