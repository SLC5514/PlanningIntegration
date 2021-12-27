<script lang="ts" setup>
useHead({
  title: 'Vitesse',
  meta: [
    { name: 'description', content: 'Opinionated Vite Starter Template' },
  ],
})
</script>

<template>
  <!-- <p class="text-center flex items-center mb-4 test">
    <router-link class="inline-block px-4 py-2 my-2" :to="{ name: 'Home' }">Home</router-link>|
    <router-link class="inline-block px-4 py-2 my-2" :to="{ name: 'About' }">About</router-link>|
    <a class="inline-block px-4 py-2 my-2" href="/test">404测试</a>
  </p>-->

  <router-view v-slot="{ Component, route }">
    <transition name="slide-fade" mode="out-in" appear>
      <component :is="Component" :key="route" />
    </transition>
  </router-view>
</template>
