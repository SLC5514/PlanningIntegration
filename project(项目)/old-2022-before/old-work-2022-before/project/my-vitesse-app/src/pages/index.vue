<script setup lang="ts">
import { useUserStore } from '~/stores/user'

const user = useUserStore()
const name = ref(user.savedName)

const router = useRouter()
const go = () => {
  if (name.value)
    router.push(`/hi/${encodeURIComponent(name.value)}`)
}

const focusTest = function (event) {
  event.target.setAttribute("readonly", "readonly");
  setTimeout(function () {
    event.target.removeAttribute("readonly");
  })
}

const blurTest = function (event) {
  setTimeout(function () {
    event.target.setAttribute("readonly", "readonly");
  })
}

const mousedownTest = function (event) {
  event.target.blur()
  event.target.focus()
}

const inputTest = function (event) {
  if (event.target.value === '') {
    mousedownTest(event)
  }
}

const { t } = useI18n()

useHead({
  title: 'Home',
  meta: [
    {
      name: 'description',
      content: 'Home description',
    },
  ],
  script: [
    {
      src: 'https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js'
    }
  ]
})
</script>

<template>
  <div>
    <p class="text-4xl">
      <!-- <carbon-campsite class="inline-block" /> -->
      <i>i</i>
    </p>
    <p>
      <a rel="noreferrer" href="https://github.com/antfu/vitesse" target="_blank">
        Vitesse
      </a>
    </p>
    <p>
      <em class="text-sm opacity-75">{{ t('intro.desc') }}</em>
    </p>

    <div class="py-4" />

    <input
      type="text"
      autocomplete="off"
      p="x-4 y-2"
      bg="transparent"
      border="~ rounded gray-200 dark:gray-700"
      outline="none active:none">
    <input
      type="password"
      autocomplete="new-password"
      readonly
      @focus="focusTest"
      @blur="blurTest"
      @mousedown="mousedownTest"
      @input="inputTest"
      p="x-4 y-2"
      bg="transparent"
      border="~ rounded gray-200 dark:gray-700"
      outline="none active:none">
    <br>

    <input
      id="input"
      v-model="name"
      :placeholder="t('intro.whats-your-name')"
      :aria-label="t('intro.whats-your-name')"
      type="text"
      autocomplete="false"
      p="x-4 y-2"
      w="250px"
      text="center"
      bg="transparent"
      border="~ rounded gray-200 dark:gray-700"
      outline="none active:none"
      @keydown.enter="go"
    >
    <label class="hidden" for="input">{{ t('intro.whats-your-name') }}</label>

    <div>
      <button
        class="m-3 text-sm btn"
        :disabled="!name"
        @click="go"
      >
        {{ t('button.go') }}
      </button>
    </div>
  </div>
</template>

<route lang="yaml">
meta:
  layout: home
</route>
