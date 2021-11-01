<template>
  <div class='about'>
    About
    <router-link to="/">Home</router-link>
  </div>
</template>

<script>
export default {
  name: 'About',
  metaInfo: {
    title: 'About',
    meta: [
      {
        name: 'keywords',
        content: 'My About'
      }
    ]
  }
}
</script>

<style scoped>

</style>
