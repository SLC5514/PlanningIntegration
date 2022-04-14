<template>
  <div class="page has-navbar" v-nav="{title: '宫格', showBackButton: true, onBackButtonClick: back}">
    <div class="page-content page-cells">
      <!-- 3 x 2 -->
      <cells :items="entrances" :on-cell-click="onCellClick" row="2"></cells>

      <!-- 3 x 3 -->
      <cells class="cells-33" :items="getItems(9)" :on-cell-click="onCellClick"></cells>

      <!-- 4 x 3 -->
      <cells class="cells-44" :items="getItems(16)" :on-cell-click="onCellClick" row="4" col="4"></cells>

      <!-- no outer border -->
      <div class="padding">
        无外边框：
      </div>
      <cells outer-border="false" class="cells-22" :items="getItems(3)" :on-cell-click="onCellClick" row="2" col="2"></cells>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Cells',
  data: () => ({
    icons: [
      'ion-android-arrow-up',
      'ion-android-arrow-down',
      'ion-android-arrow-back',
      'ion-android-arrow-forward',

      'ion-ios-arrow-up',
      'ion-ios-arrow-down',
      'ion-ios-arrow-left',
      'ion-ios-arrow-right',

      'ion-ios-arrow-thin-up',
      'ion-ios-arrow-thin-down',
      'ion-ios-arrow-thin-left',
      'ion-ios-arrow-thin-right',

      'ion-android-arrow-up',
      'ion-android-arrow-down',
      'ion-android-arrow-back',
      'ion-android-arrow-forward',
    ],

    entrances: [
      '<div class="entrance assertive"><i class="icon ion-ios-flame"></i><br><span>热门</span></div>',
      '<div class="entrance energized"><i class="icon ion-ios-star"></i><br><span>好评</span></div>',
      '<div class="entrance balanced"><i class="icon ion-ios-location"></i><br><span>附近</span></div>',
      '<div class="entrance positive"><i class="icon ion-ios-search"></i><br><span>搜索</span></div>',
    ]
  }),
  methods: {
    back () {
      $router.back('/');
    },
    onCellClick(cellIndex) {
      console.log('cell ' + cellIndex + ' clicked');
    },
    getIcon(iconName, color) {
      return '<i class="' + iconName + '"></i>';
    },
    getItems(n) {
      let items = [];
      for (let i = 0; i < n; i++) {
        items.push(this.getIcon(this.icons[i]));
      }
      return items;
    }
  }
}
</script>
<style scoped>
.button {
  margin-top: 0;
}
</style>
