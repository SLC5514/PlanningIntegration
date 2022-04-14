<script lang="ts" setup>
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();

const { params, query } = route
const { path } = params

router.replace({ path: '/' + encodeURI(Array.isArray(path) ? path[0] || '' : path), query })
</script>

<template>
  <div>重定向</div>
</template>
