<template>
  <section class="home">
    <div class="store-box">
      <div>{{ storeNum }}</div>
      <button @click="ADD_NUM">
        add
      </button>
    </div>
    <Common :params="params">
      <div slot="title">
        nuxt-demo
      </div>
      <div slot="subtitle">
        A nuxt-demo
      </div>
    </Common>
  </section>
</template>

<script>
import { mapMutations } from 'vuex'

export default {
  name: 'Home',
  components: {
    Common: () => import('~/components/Common')
  },
  data() {
    return {
      params: {
        name: 'aaa',
        id: 123
      }
    }
  },
  computed: {
    storeNum() {
      return this.$store.state.num
    }
  },
  mounted() {
    this.$axios.get('/test', function(res) {
      console.log(res)
    })
    console.log(this.$route)
    // 页面加载进度条
    this.$nextTick(() => {
      this.$nuxt.$loading.start()
      setTimeout(() => this.$nuxt.$loading.finish(), 500)
    })
  },
  methods: {
    ...mapMutations(['ADD_NUM'])
  }
}
</script>

<style lang="less">
.home {
  .store-box {
    position: absolute;
    top: 100px;
    width: 100%;
    text-align: center;
    color: #666;
    font-size: 24px;
    button {
      display: inline-block;
      border-radius: 4px;
      border: 1px solid #35495e;
      color: #35495e;
      text-decoration: none;
      padding: 10px 30px;
      margin-top: 15px;
      background-color: transparent;
      outline: none;
      cursor: pointer;
      &:hover {
        color: #fff;
        background-color: #35495e;
      }
    }
  }
  .title {
    color: #333;
  }
}
// 页面过渡动效
.page-enter-active,
.page-leave-active {
  transition: opacity 0.5s;
}
.page-enter,
.page-leave-active {
  opacity: 0;
}
</style>
