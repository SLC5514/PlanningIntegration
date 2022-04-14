<script lang="ts" setup>
import { useAppStore } from '~/stores/app'

const appStore = useAppStore()

useHead({
  title: `404-${appStore.title}`,
  meta: [
    { name: 'description', content: `404-${appStore.description}` },
  ],
})
</script>

<route lang="yaml">
meta:
  layout: 404
</route>

<template>
  <div>not-found</div>
</template>
