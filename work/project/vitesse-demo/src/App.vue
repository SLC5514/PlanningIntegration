<script lang="ts" setup>
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup

// https://github.com/vueuse/head
// you can use this to manipulate the document head in any components,
// they will be rendered correctly in the html results with vite-ssg
useHead({
  title: 'Vitesse',
  meta: [
    { name: 'description', content: 'Opinionated Vite Starter Template' },
  ],
})
</script>

<template>
  <!-- <img alt="Vue logo" src="./assets/logo.png" /> -->
  <!-- <HelloWorld msg="Hello Vue 3 + TypeScript + Vite" /> -->

  <p class="text-center flex items-center test">
    <router-link class="inline-block px-4 py-2 my-2" :to="{ name: 'Home' }">Home</router-link> |
    <router-link class="inline-block px-4 py-2 my-2" :to="{ name: 'About' }">About</router-link> |
    <a class="inline-block px-4 py-2 my-2" href="/test">404</a>
  </p>

  <router-view v-slot="{ Component, route }">
    <transition name="slide-fade" mode="out-in" appear>
      <component :is="Component" :key="route" />
    </transition>
  </router-view>
</template>

<style lang="scss">
.test {
  color: #333;
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 1
}
</style>
