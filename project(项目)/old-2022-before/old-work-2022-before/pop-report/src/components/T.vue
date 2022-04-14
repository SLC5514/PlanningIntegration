<script lang="ts" setup>
const { t } = useI18n()
const { k } = defineProps<{
  k: string
}>()
</script>

<template>
  {{ t(k) }}
</template>
