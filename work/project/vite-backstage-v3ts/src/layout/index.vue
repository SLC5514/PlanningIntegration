<template>
  <div :class="classObj" class="app-wrapper">
    <div v-if="device==='mobile'&&sidebar.opened" class="drawer-bg" @click="handleClickOutside" />
    <!-- <sidebar class="sidebar-container" /> -->
    <div :class="{hasTagsView:needTagsView}" class="main-container">
      <div :class="{'fixed-header':fixedHeader}">
        <!-- <navbar /> -->
        <!-- <tags-view v-if="needTagsView" /> -->
      </div>
      <!-- <app-main /> -->
      <!-- <right-panel v-if="showSettings">
        <settings />
      </right-panel> -->
      <el-button type="primary">主要按钮 {{currentPage}}</el-button>
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="currentPage"
        :page-sizes="[100, 200, 300, 400]"
        :page-size="100"
        layout="total, sizes, prev, pager, next, jumper"
        :total="400">
      </el-pagination>
      <router-view />
    </div>
  </div>
</template>

<script>
// import RightPanel from '@/components/RightPanel'
// import { AppMain, Navbar, Settings, Sidebar, TagsView } from './components'
// import ResizeMixin from './mixin/ResizeHandler'
// import { mapState } from 'vuex'

export default {
  name: 'Layout',
  components: {
    // AppMain,
    // Navbar,
    // RightPanel,
    // Settings,
    // Sidebar,
    // TagsView
  },
  data() {
    return {
      sidebar: false,
      device: false,
      showSettings: false,
      needTagsView: false,
      fixedHeader: false,
      currentPage: 4
    }
  },
  // mixins: [ResizeMixin],
  computed: {
    // ...mapState({
    //   sidebar: state => state.app.sidebar,
    //   device: state => state.app.device,
    //   showSettings: state => state.settings.showSettings,
    //   needTagsView: state => state.settings.tagsView,
    //   fixedHeader: state => state.settings.fixedHeader
    // }),
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile'
      }
    }
  },
  methods: {
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    },
    handleSizeChange(val) {
      console.log(`每页 ${val} 条`);
    },
    handleCurrentChange(val) {
      console.log(`当前页: ${val}`);
      this.currentPage = val;
    }
  }
}
</script>

<style lang="scss" scoped>
  @import "@/styles/mixin.scss";
  @import "@/styles/variables.scss";

  .app-wrapper {
    @include clearfix;
    position: relative;
    height: 100%;
    width: 100%;

    &.mobile.openSidebar {
      position: fixed;
      top: 0;
    }
  }

  .drawer-bg {
    background: #000;
    opacity: 0.3;
    width: 100%;
    top: 0;
    height: 100%;
    position: absolute;
    z-index: 999;
  }

  .fixed-header {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 9;
    width: calc(100% - #{$sideBarWidth});
    transition: width 0.28s;
  }

  .hideSidebar .fixed-header {
    width: calc(100% - 54px)
  }

  .mobile .fixed-header {
    width: 100%;
  }
</style>
