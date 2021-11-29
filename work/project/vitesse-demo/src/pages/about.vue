<script lang="ts" setup>
useHead({
  title: 'About',
  meta: [
    { name: 'description', content: "About Description" }
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
About
<button @click="user.setName('About')">修改：{{user.getName}}</button>
<p>{{ t('intro.desc') }}</p>

<a class="icon-btn mx-2" :title="t('button.toggle_langs')" @click="toggleLocales">
  <!-- <carbon-language /> -->
  <i>Lang</i>
</a>
</template>

<route lang="yaml">
name: About
meta:
  layout: default
</route>
