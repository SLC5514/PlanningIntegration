<template>
<div>
  <label>宽度：</label>
  <input type="number" v-model="width">
</div>
<div>
  <label>高度：</label>
  <input type="number" v-model="height">
</div>
<div>
  <label>文案：</label>
  <input type="text" v-model="text">
</div>
<Button/>


</template>

<script lang="ts">
import { defineComponent, defineAsyncComponent, ref } from 'vue'

export default defineComponent({
  name: 'App',
  components: {
    Button: defineAsyncComponent(() => import('@/components/custom/button'))
  },
  computed: {
    btnStyle() {
      return {
        width: this.width,
        height: this.height,
        text: this.text
      }
    }
  },
  setup() {
    const width = ref(100);
    const height = ref(100);
    const text = ref('按钮');

    return {
      width,
      height,
      text
    }
  },
})
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
