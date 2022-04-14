<template>
  <div class="page has-navbar page-scalable" v-nav="{title: '缩放', showBackButton: true, onBackButtonClick: back}">
    <div class="page-content">
      <scalable width="100%" height="100" style="background-color: #FFF;">
        <p>
          Font Size = 16px (相当于8px)
        </p>
      </scalable>

      <div class="item item-divider">
        带特小字体的标签：
      </div>

      <scalable width="100%" height="30">
        <div class="tags">
          <div class="tag">免费</div>
          <div class="tag tag-assertive">
            剩余<span class="num">1000</span>份
          </div>
        </div>
      </scalable>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Scalable',
  data: () => ({}),
  methods: {
    back () {
      $router.back('/')
    }
  }
}
</script>
