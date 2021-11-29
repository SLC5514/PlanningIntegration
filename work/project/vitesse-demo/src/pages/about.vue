<script lang="ts" setup>
useHead({
  title: 'About',
  meta: [
    { name: 'description', content: "About Description" }
  ]
})

import { useUserStore } from '~/stores/user'
const user = useUserStore()
</script>

<template>
  <p>About</p>
  <button class="m-3 text-sm btn" @click="user.setName('About')">修改：{{ user.getName }}</button>
</template>

<route lang="yaml">
name: About
meta:
  layout: default
</route>
