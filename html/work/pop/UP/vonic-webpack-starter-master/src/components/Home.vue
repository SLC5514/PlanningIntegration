<template>
  <div class="page has-navbar" v-nav="{ title: 'Home' }">
    <div class="page-content text-center">
      <router-link class="button button-positive" to="/button">Button</router-link>
      <router-link class="button button-positive" to="/input">Input</router-link>
      <router-link class="button button-royal" to="/search">Search</router-link>
      <router-link class="button button-positive" to="/radio">Radio</router-link>
      <router-link class="button button-positive" to="/checkbox">Checkbox</router-link>
      <router-link class="button button-positive" to="/toggle">Toggle</router-link>
      <router-link class="button button-positive" to="/range">Range</router-link>
      <router-link class="button button-positive" to="/header">Header</router-link>
      <router-link class="button button-royal" to="/badge">Badge</router-link>
      <router-link class="button button-royal" to="/list">List</router-link>
      <router-link class="button button-royal" to="/cells">Cells</router-link>
      <router-link class="button button-royal" to="/tabs">Tabs</router-link>
      <router-link class="button button-royal" to="/buttonBar">ButtonBar</router-link>
      <router-link class="button button-royal" to="/scalable">Scalable</router-link>
      <router-link class="button button-royal" to="/swiper">Swiper</router-link>
      <router-link class="button button-royal" to="/scroll">Scroll</router-link>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'Home',
    data: () => ({})
  }
</script>
<style scoped>
  h2 {
    font-family: Candara,Calibri,Segoe,Segoe UI,Optima,Arial,sans-serif;
    color: #888;
  }
  .button {
    margin-top: 10px;
  }
</style>
