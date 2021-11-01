<template>
  <div class="page has-navbar" v-nav="{title: '徽章', showBackButton: true, onBackButtonClick: back}">
    <div class="page-content">
      <div class="padding">
        <badge num="2"></badge>
        <badge num="35"></badge>
        <badge num="99<sup>+</sup>"></badge>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Badge',
  data: () => ({}),
  methods: {
    back () {
      $router.back('/');
    }
  }
}
</script>
<style scoped>
.button {
  margin-top: 0;
}
</style>
