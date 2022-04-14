<template>
  <div class="about">
    <Common>
      About
    </Common>
  </div>
</template>

<script>
export default {
  name: 'About',
  components: {
    Common: () => import('~/components/Common.vue')
  }
}
</script>

<style lang="less" scoped>
</style>
