<script lang="ts" setup>
const test = 123
</script>

<template>
  <div id="layout">
    <div class="color">{{ test }}</div>
    <slot />
  </div>
</template>

<style>
.color {
  color: red;
}
</style>
