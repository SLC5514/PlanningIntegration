<template>
  <section class="container">
    <Common>
      about
    </Common>
  </section>
</template>

<script>
export default {
  name: 'About',
  head: {
    title: '鞋子设计在线_中国最专业的鞋样设计、鞋子图片素材资讯网站',
    titleTemplate: '%s - About',
    meta: [
      {
        hid: 'description',
        name: 'description',
        content:
          '国际鞋子设计在线提供最专业的鞋子图片,鞋样设计,鞋子设计图片下载以及资讯服务,是国内最权威的鞋子网,鞋样网。推出知名品牌的鞋样设计，鞋子款式图的自制鞋子流行杂志书刊。'
      },
      {
        hid: 'keywords',
        name: 'keywords',
        content: '鞋样设计,鞋子款式,鞋子设计,鞋子设计图,鞋子图片'
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
