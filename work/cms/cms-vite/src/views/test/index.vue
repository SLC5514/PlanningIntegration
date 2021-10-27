<script lang="ts" setup>
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'

const store = useStore()
const router = useRouter()
const route = useRoute()

const logout = async function () {
  await store.dispatch('user/logout')
  router.push({ path: '/login', query: { redirect: route.fullPath } })
}
</script>

<template>
<el-button type="primary" @click.native="logout">退出登录</el-button>
test
</template>

<style lang="scss" scoped>
</style>
