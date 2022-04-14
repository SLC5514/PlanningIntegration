<template>
  <div class="page has-navbar" v-nav="{title: '复选框', showBackButton: true, onBackButtonClick: back}">
    <div class="page-content">
      <div class="item item-divider">
        选择感兴趣的板块:
      </div>

      <von-checkbox :options="topics" v-model="chosenTopics" theme="positive"></von-checkbox>

      <p class="padding">
        chosen topics: {{ chosenTopics }}
      </p>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Checkbox',
  data() {
    return {
      chosenTopics: [],
      topics: ["娱乐", "电影", "减肥", "搞笑", "科技"]
    }
  },
  methods: {
    back () {
      $router.back('/');
    }
  }
}
</script>
