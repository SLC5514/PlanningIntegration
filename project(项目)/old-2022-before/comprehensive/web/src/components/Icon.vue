<template>
  <PieChartOutlined v-if="name === 'PieChartOutlined'" />
  <DesktopOutlined v-if="name === 'DesktopOutlined'" />
  <UserOutlined v-if="name === 'UserOutlined'" />
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import {
  PieChartOutlined,
  DesktopOutlined,
  UserOutlined,
} from '@ant-design/icons-vue';

export default defineComponent({
  name: 'Icon',
  props: {
    name: {
      type: String,
      default: ''
    }
  },
  components: {
    PieChartOutlined,
    DesktopOutlined,
    UserOutlined,
  }
})
</script>
