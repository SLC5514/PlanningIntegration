<script setup lang="ts">
import { Head } from '@vueuse/head'
import { useAppStore } from '~/stores/app'

const appStore = useAppStore()

appStore.title = `${appStore.title}-Default`
appStore.description = `${appStore.description}-Default`

const loading = ref(false)
</script>

<template>
  <div class="layout-default" v-loading="loading">
    <Head>
      <title>{{appStore.title}}</title>
      <meta name="description" :content="appStore.description">
    </Head>
    <router-view />
  </div>
</template>
