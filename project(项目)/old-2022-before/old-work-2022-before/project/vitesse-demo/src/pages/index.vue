<script lang="ts" setup>
// import { Head } from '@vueuse/head';
import { useUserStore } from '~/stores/user'

useHead({
  title: 'Home',
  meta: [
    { name: 'description', content: 'Home Description' },
  ],
})

const { t } = useI18n()
const user = useUserStore()
</script>

<template>
  <!-- <Head>
  <title>Test</title>
  <meta name="description" content="Home Description">
  </Head>-->
  <p>Home</p>
  <button class="m-3 text-sm btn" @click="user.setName('Home')">修改：{{ user.getName }}</button>
  <p>
    <a rel="noreferrer" href="https://github.com/antfu/vitesse" target="_blank">Vitesse</a>
  </p>
  <p>
    <em class="text-sm opacity-75">{{ t('intro.desc') }}</em>
  </p>
</template>

<route lang="yaml">
name: Home
meta:
  layout: default
</route>
