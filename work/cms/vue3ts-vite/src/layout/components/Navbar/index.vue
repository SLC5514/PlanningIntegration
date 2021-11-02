<script lang="ts" setup>
import { useRoute } from 'vue-router';

const route = useRoute();
</script>

<template>
  <div class="navbar">
    <span class="breadcrumb">
      <template v-for="(item, index) in route.matched" :key="index">
        <span class="breadcrumb-item">
          <span class="separator">/</span>
          <router-link
            :to="item.path"
            custom
            v-slot="{ href, route, navigate, isActive, isExactActive }"
          >
            <a
              v-if="!isExactActive"
              :class="[isActive && 'router-link-active', isExactActive && 'router-link-exact-active']"
              :href="href"
              @click="navigate"
            >{{ route.name === 'layout' ? '首页' : route.name }}</a>
            <span
              v-else
              :class="[isActive && 'router-link-active', isExactActive && 'router-link-exact-active']"
            >{{ route.name }}</span>
          </router-link>
        </span>
      </template>
    </span>
  </div>
</template>

<style lang="scss" scoped>
.navbar {
  height: $navbar-height;
  line-height: $navbar-height;
  padding: 0 15px;
  background: #fff;
  box-shadow: 0 1px 4px rgb(0 21 41 / 8%);
  .breadcrumb {
    font-size: 14px;
    .breadcrumb-item {
      &:first-child > .separator {
        display: none;
      }
      .separator {
        margin: 0 5px;
      }
      .router-link-active {
        transition: color 0.3s;
      }
      .router-link-exact-active {
        color: #97a8be;
      }
    }
  }
}
</style>
