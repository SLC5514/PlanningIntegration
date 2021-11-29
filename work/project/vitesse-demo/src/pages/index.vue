<script lang="ts" setup>
// import { Head } from '@vueuse/head';
useHead({
  title: 'Home',
  meta: [
    { name: 'description', content: "Home Description" }
  ]
})

const { t, locale, availableLocales } = useI18n()

const toggleLocales = () => {
  // change to some real logic
  const locales = availableLocales
  locale.value = locales[(locales.indexOf(locale.value) + 1) % locales.length]
}

import { useUserStore } from '~/stores/user'
const user = useUserStore()
</script>

<template>
<!-- <Head>
  <title>Test</title>
  <meta name="description" content="Home Description">
</Head> -->

Home
<button @click="user.setName('Home')">修改：{{user.getName}}</button>
<p>{{ t('intro.desc') }}</p>

<a class="icon-btn mx-2" :title="t('button.toggle_langs')" @click="toggleLocales">
  <!-- <carbon-language /> -->
  <i>Lang</i>
</a>
</template>

<route lang="yaml">
name: Home
meta:
  layout: default
</route>
