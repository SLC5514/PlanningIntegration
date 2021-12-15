<script lang="ts" setup>
import { useUserStore } from './stores/user'
import imgUrl from '~/assets/思源黑体-粗体.woff'
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

const store = useUserStore()
console.log(store)

onMounted(() => {
  const font = new FontFace(
    '思源',
    `url(${imgUrl})`,
    // `url(${location.href.split('#')[0]}src/assets/思源黑体-粗体.woff)`,
    // `url(${location.href.split('#')[0]}src/assets/SourceHanSansCN-Bold.woff)`,
    // `url(${location.href.split('#')[0]}src/assets/SourceHanSansCN-Bold2.otf)`,
  )
  font.load().then(() => {
    document.fonts.add(font)
    document.body.classList.add('fonts-loaded')
  })
})
</script>

<template>
  <!-- <img alt="Vue logo" src="./assets/logo.png" /> -->
  <!-- <HelloWorld msg="Hello Vue 3 + TypeScript + Vite" /> -->

  <p class="text-center flex items-center mb-4 test">
    <router-link class="inline-block px-4 py-2 my-2" :to="{ name: 'Home' }">Home</router-link>|
    <router-link class="inline-block px-4 py-2 my-2" :to="{ name: 'About' }">About</router-link>|
    <a class="inline-block px-4 py-2 my-2" href="/test">404测试</a>
  </p>

  <router-view v-slot="{ Component, route }">
    <transition name="slide-fade" mode="out-in" appear>
      <component :is="Component" :key="route" />
    </transition>
  </router-view>
  <p>
    <Parent />
  </p>
</template>

<style lang="scss" scoped>
// @font-face {
//   font-family: "思源";
//   src: url(~/assets/font.woff) format("woff");
// }
.test {
  color: #333;
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 1;
  font-family: "思源", "Arial", sans-serif;
}
</style>
<style lang="scss">
.fonts-loaded .test {
  color: #999;
}
</style>
