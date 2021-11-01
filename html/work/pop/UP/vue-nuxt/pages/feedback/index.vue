<template>
  <div class="feedback">
    <Common>
      Feedback
    </Common>
  </div>
</template>

<script>
export default {
  name: 'Feedback',
  components: {
    Common: () => import('~/components/Common.vue')
  }
}
</script>

<style lang="less" scoped>
</style>
