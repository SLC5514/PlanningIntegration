<script lang="ts" setup>
import { ref } from "vue";
import { routes } from '~/router';
import logo from "~/assets/logo.png";

const expandedKeys = ref({})
const menuList = ref(transRoutes(routes))

interface routeItem {
  path: string
  name: string | symbol
  mate?: AnyObject
  children?: Array<routeItem>
}

interface routeMenuItem {
  key: string
  label: string | symbol
  icon: string
  path: string
  items?: Array<routeMenuItem>
}

function transRoutes(routes: Array<routeItem>, parentKey?: string): Array<routeMenuItem> {
  const arr = []
  for (let i = 0; i < routes.length; i++) {
    const item: routeMenuItem = {
      key: (parentKey ? parentKey + '_' : '') + i + '',
      label: routes[i]?.name || '',
      icon: routes[i]?.mate?.icon || 'pi pi-fw pi-file',
      ...routes[i]
    }
    let items: Array<routeMenuItem> = []
    if (routes[i].children) {
      items = transRoutes(routes[i].children || [], item.key)
      item.items = items
    }
    arr.push(item)
  }
  return arr
}

console.log(menuList.value)
</script>

<template>
  <div class="layout-container flex">
    <div class="sidebar-container">
      <div class="sidebar-logo-container">
        <a class="sidebar-logo-link" href="/">
          <img class="sidebar-logo" :src="logo" alt="" />
          <h1 class="sidebar-title">CMS Admin</h1>
        </a>
      </div>
      <!-- <PanelMenu class="sidebar-menu" :model="menuList" v-model:expandedKeys="expandedKeys" /> -->
      <PanelMenu class="sidebar-menu" :model="menuList" :expandedKeys="expandedKeys">
        <template #item="{ item }">
          <router-link :to="item.path" custom v-slot="{ href, route, navigate, isActive, isExactActive }">
            <a v-if="item.items && item.items.length > 0" :class="{
              'active-link': isActive,
              'active-link-exact': isExactActive
            }" href="javascript:void(0);"
            @click="expandedKeys[item.key] = !expandedKeys[item.key]">{{route.name}}-{{route.fullPath}}</a>
            <a v-else :class="{
              'active-link': isActive,
              'active-link-exact': isExactActive
            }" :href="href" @click="navigate">{{route.name}}-{{route.fullPath}}</a>
          </router-link>
        </template>
      </PanelMenu>
      {{expandedKeys}}
    </div>
    <div class="main-container flex-auto">
      <div class="header-container">
        <div class="navbar">navbar</div>
        <div class="tagbar">tagbar</div>
      </div>
      <div class="app-main">
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
        main <br />
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.layout-container {
  min-height: 100vh;
  .sidebar-container {
    color: $sidebar-color;
    background-color: $sidebar-bgcolor;
    width: $sidebar-width-open;
    transition: width 0.28s;
    height: 100%;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 9;
    overflow: hidden;
    .sidebar-logo-container {
      position: relative;
      width: 100%;
      height: 50px;
      line-height: 50px;
      background: #2b2f3a;
      text-align: center;
      overflow: hidden;
      .sidebar-logo-link {
        display: inline-block;
        width: 100%;
        height: 100%;
        overflow: hidden;
        .sidebar-logo {
          width: 32px;
          height: 32px;
          vertical-align: middle;
          margin-right: 12px;
        }
        .sidebar-title {
          display: inline-block;
          margin: 0;
          color: #fff;
          font-weight: 600;
          line-height: 50px;
          font-size: 14px;
          font-family: Avenir, Helvetica Neue, Arial, Helvetica, sans-serif;
          vertical-align: middle;
        }
      }
    }
    .sidebar-menu {
      a {
        display: block;
        padding: 1rem;
      }
    }
  }
  .main-container {
    transition: margin-left 0.28s;
    margin-left: $sidebar-width-open;
    position: relative;
    min-height: 100%;
    .header-container {
      position: fixed;
      top: 0;
      right: 0;
      z-index: 9;
      width: calc(100% - $sidebar-width-open);
      transition: width 0.28s;
      .navbar {
        height: $navbar-height;
        overflow: hidden;
        position: relative;
        background: #fff;
        box-shadow: 0 1px 4px rgb(0 21 41 / 8%);
      }
      .tagbar {
        height: $tagbar-height;
        width: 100%;
        background: #fff;
        border-bottom: 1px solid #d8dce5;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 12%), 0 0 3px 0 rgb(0 0 0 / 4%);
      }
    }
    .app-main {
      min-height: calc(100vh - ($navbar-height + $tagbar-height));
      padding-top: $navbar-height + $tagbar-height;
    }
  }
}
</style>
