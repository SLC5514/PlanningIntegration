<template>
  <button :style="pxStyle">{{elText || '按钮'}}</button>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'

import { getPxStyle } from '../utils'

export default defineComponent({
  name: 'Button',
  props: {
    elStyle: {
      type: Object,
      default: {}
    },
    elText: {
      type: String,
      default: '按钮'
    },
  },
  setup(props) {
    const pxStyle = computed(() => getPxStyle(props.elStyle))

    return {
      pxStyle
    }
  }
})
</script>

<style lang="scss" scoped>
</style>
