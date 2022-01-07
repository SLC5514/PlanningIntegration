<script setup lang="ts">
import { useAppStore } from '~/stores/app';

const appStore = useAppStore()

appStore.title = 'POP报告后台-Default'
appStore.description = 'POP报告后台-Default'

useHead({
  title: appStore.title,
  meta: [
    { name: 'description', content: appStore.description },
  ],
})
</script>

<template>
  <div class="layout-default">
    <router-view />
  </div>
</template>
