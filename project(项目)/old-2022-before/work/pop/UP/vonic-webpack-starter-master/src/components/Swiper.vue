<template>
  <div class="page has-navbar page-scalable" v-nav="{title: '轮播', showBackButton: true, onBackButtonClick: back}">
    <div class="page-content page-swiper">
      <swiper ref="swiper" direction="horizontal" width="100%" height="150" pager-color="#ea5a49" pager-bg-color="#e5e4e3">
        <swiper-item>
          <h1 style="color: #0a9dc7;" @click="onClick()">Item 1</h1>
        </swiper-item>

        <swiper-item>
          <h1 style="color: #44cc00;">Item 2</h1>
        </swiper-item>

        <swiper-item>
          <h1 style="color: #ffc900;">Item 3</h1>
        </swiper-item>
      </swiper>

      <div class="row">
        <div class="col col-50">
          <button class="button button-assertive button-block" @click="prev()">prev</button>
        </div>

        <div class="col col-50">
          <button class="button button-assertive button-block" @click="next()">next</button>
        </div>
      </div>

      <p class="padding">请在手机中查看该 demo</p>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Swiper',
  data: () => ({}),
  methods: {
    back () {
      $router.back('/')
    },
    next() {
      this.$refs.swiper.next()
    },
    prev() {
      this.$refs.swiper.prev()
    }
  }
}
</script>
