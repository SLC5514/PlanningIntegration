<template>
  <div class="home">
    Home
    <router-link to="/about">About</router-link>
    <router-link to="/about/us">Us</router-link>
  </div>
</template>

<script>
export default {
  name: 'Home',
  metaInfo: {
    title: 'Home',
    meta: [
      {
        name: 'keywords',
        content: 'My Home'
      }
    ]
  }
}
</script>

<style scoped>

</style>
