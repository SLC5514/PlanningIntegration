<script lang="ts" setup>
import { useAppStore } from '~/stores/app';

const appStore = useAppStore()

appStore.title = 'POP报告后台-404'
appStore.description = 'POP报告后台-404'

useHead({
  title: appStore.title,
  meta: [
    { name: 'description', content: appStore.description },
  ],
})

const router = useRouter()
</script>

<template>
  <div class="layout-404">
    <div>Layout 404</div>
    <button class="btn m-3 text-sm mt-8" @click="router.back()">back</button>
    <router-view />
  </div>
</template>
