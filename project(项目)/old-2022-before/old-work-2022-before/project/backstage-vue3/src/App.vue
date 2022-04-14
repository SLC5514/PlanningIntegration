<template>
  <router-view />
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "App",
});
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  min-height: 100vh;
  // display: flex;
  // flex: auto;
  // flex-direction: row;
  color: #2c3e50;
  background: #f0f2f5;
  // .layout {
  //   min-height: 100vh;
  //   display: flex;
  //   flex: auto;
  //   flex-direction: column;
  //   background: #f0f2f5;
  // }
}
</style>
