<script setup lang="ts">
import { isDark, toggleDark } from '~/composables'

const { t, availableLocales, locale } = useI18n()

const toggleLocales = () => {
  // change to some real logic
  const locales = availableLocales
  locale.value = locales[(locales.indexOf(locale.value) + 1) % locales.length]
}
</script>

<template>
  <nav class="text-xl mt-6">
    <router-link class="icon-btn mx-2" to="/" :title="t('button.home')">
      <!-- <carbon-campsite /> -->
      <i>Home</i>
    </router-link>

    <button class="icon-btn mx-2 !outline-none" :title="t('button.toggle_dark')" @click="toggleDark()">
      <!-- <carbon-moon v-if="isDark" />
      <carbon-sun v-else /> -->
      <i v-if="isDark">月</i>
      <i v-else>日</i>
    </button>

    <a class="icon-btn mx-2" :title="t('button.toggle_langs')" @click="toggleLocales">
      <!-- <carbon-language /> -->
      <i>Lang</i>
    </a>

    <router-link class="icon-btn mx-2" to="/about" :title="t('button.about')">
      <!-- <carbon-dicom-overlay /> -->
      <i>About</i>
    </router-link>

    <a class="icon-btn mx-2" rel="noreferrer" href="https://github.com/antfu/vitesse" target="_blank" title="GitHub">
      <!-- <carbon-logo-github /> -->
      <i>GitHub</i>
    </a>
  </nav>
</template>
