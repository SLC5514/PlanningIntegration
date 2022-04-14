
<template>
  <!-- <img src="@/assets/images/loading.gif" class="loading"> -->
  <div id="layout">
    <div id="header">
      <img src="@/assets/images/SLC@2x.png" class="logo">
      <div class="search">
        <input type="text" placeholder="搜索">
      </div>
      <div class="other">other</div>
    </div>
    <div class="flex">
      <div id="left-bar">
        <div class="lb-top">
          <div class="active"><img src=""><span>消息</span></div>
          <div><img src=""><span>日程</span></div>
          <div><img src=""><span>通讯录</span></div>
        </div>
      </div>
      <div id="content">
        <div id="ctx-aside">
          <div class="active">
            <img src="">
            <div>
              <div class="title">聊天群组一</div>
              <div class="info-msg">路人甲：阿巴阿巴阿巴</div>
            </div>
          </div>
          <div>
            <img src="">
            <div>
              <div class="title">聊天群组一</div>
              <div class="info-msg">路人甲：阿巴阿巴阿巴</div>
            </div>
          </div>
        </div>
        <div id="ctx-edit">content</div>
      </div>
      <div id="right-aside">right-aside</div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
// import { mapState } from 'vuex'

export default defineComponent({
  // data() {
  //   return {
  //   }
  // },
  // computed: {
  //   ...mapState({
  //     indexData: state => state.indexStore?.data
  //   })
  // }
  setup() {
    return {
      data: 123
    }
  }
})
</script>

<style lang="less" scoped>
@import '../../variables.less';

#layout {
  height: 100vh;
  display: flex;
  flex-direction: column;
  & > .flex {
    display: flex;
    flex: 1;
  }
}
#header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  height: @headerHeight;
  color: #fff;
  background-color: #ccc;
  .logo {
    width: 50px;
    margin: 0 15px;
  }
  .search {
    width: 50%;
    height: 36px;
    input {
      width: 100%;
      height: 100%;
      padding: 0 10px;
      outline: none;
      border: none;
      border-radius: 5px;
      background-color: #eee;
    }
  }
}
#left-bar {
  width: @leftBarWidth;
  height: 100%;
  padding: 8px;
  background-color: #EBEBEB;
  box-sizing: border-box;
  .lb-top {
    font-size: 12px;
    text-align: center;
    & > div {
      padding: 8px;
      border-radius: 5px;
      box-sizing: border-box;
      cursor: pointer;
      overflow: hidden;
      & + div {
        margin-top: 2px;
      }
      &:hover {
        color: #666;
        background-color: #DDDEDF;
      }
      &.active {
        color: #333;
        background-color: #D1D3D5;
      }
    }
    img {
      display: block;
      width: 30px;
      height: 30px;
      margin: 0 auto 4px;
      background-color: #eee;
    }
  }
}
#content {
  flex: 1;
  display: flex;
  #ctx-aside {
    width: 200px;
    height: 100%;
    padding: 8px;
    background-color: #ffffff;
    box-sizing: border-box;
    & > div {
      width: 100%;
      padding: 8px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      box-sizing: border-box;
      cursor: pointer;
      & + div {
        margin-top: 2px;
      }
      &:hover {
        background-color: #F6F6F7;
      }
      &.active {
        background-color: #F1F2F3;
      }
      img {
        width: 40px;
        height: 40px;
        margin-right: 5px;
        border-radius: 5px;
        background-color: #eee;
      }
      .title {
        margin-bottom: 5px;
        font-size: 16px;
        white-space: nowrap;
      }
      .info-msg {
        font-size: 12px;
        white-space: nowrap;
      }
    }
  }
  #ctx-edit {
    flex: 1;
    background-color: #F1F2F3;
  }
}
#right-aside {
  width: @rightAsideWidth;
  height: 100%;
  background-color: #FAFAFA;
}
</style>
