<template>
  <section class="container">
    <Common>
      feedback
    </Common>
  </section>
</template>

<script>
export default {
  name: 'Feedback',
  head: {
    title:
      '史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯',
    titleTemplate: '%s - Feedback',
    meta: [
      {
        hid: 'description',
        name: 'description',
        content:
          '史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯'
      },
      {
        hid: 'keywords',
        name: 'keywords',
        content:
          '史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯'
      }
    ]
  },
  components: {
    Common: () => import('~/components/Common.vue')
  },
  data() {
    return {}
  },
  validate({ params }) {
    console.log(params)
    return /^\d+$/.test(params.id)
  },
  computed: {},
  mounted() {
    this.$nextTick(() => {
      this.$nuxt.$loading.start()

      setTimeout(() => this.$nuxt.$loading.finish(), 500)
    })
  },
  methods: {}
}
</script>

<style lang="less">
</style>
