<template>
  <div class="home">
    <h1>This is an home page</h1>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useHead } from '@vueuse/head'

export default defineComponent({
  name: 'Home',
  setup() {
    useHead({
      title: 'Home Title',
      meta: [
        {
          name: `description`,
          content: `Home description`,
        },
      ],
    })
  }
});
</script>
