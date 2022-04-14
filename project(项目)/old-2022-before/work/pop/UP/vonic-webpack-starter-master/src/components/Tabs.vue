<template>
  <div class="page has-navbar" v-nav="{title: '选项卡', showBackButton: true, onBackButtonClick: back}">
    <tabs :tab-items="tabs" :tab-index="tabIndex" :on-tab-click="onTabClick"></tabs>

    <div class="page-content">
      <p>
        Active Tab Index: {{ tabIndex }}
      </p>

      <p>
        active category index: {{ categories[categoryIndex] }}
      </p>
    </div>

    <tabs :tab-items="categories"
          :tab-index="categoryIndex"
          position="bottom"
          bg-color="assertive"
          tab-color="light"
          :on-tab-click="onCategoryClick"
    ></tabs>
  </div>
</template>
<script>
export default {
  name: 'Tabs',
  data: () => ({
    tabs: [
      "tab 1",
      "tab 2",
      "tab 3"
    ],

    tabIndex: 0,

    categories: [
      "女装",
      "男装",
      "内衣",
      "鞋靴",
      "箱包",
      "更多"
    ],

    categoryIndex: 0
  }),
  methods: {
    back () {
      $router.back('/');
    },
    onTabClick(index) {
      console.log(index)
      this.tabIndex = index
    },

    onCategoryClick(index) {
      this.categoryIndex = index
      console.log('category changed, current tab index is:', index);
    }
  }
}
</script>
<style scoped>
.button {
  margin-top: 0;
}
</style>
