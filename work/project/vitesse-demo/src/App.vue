<script lang="ts" setup>
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup

// https://github.com/vueuse/head
// you can use this to manipulate the document head in any components,
// they will be rendered correctly in the html results with vite-ssg
useHead({
  title: 'Vitesse',
  meta: [
    { name: 'description', content: 'Opinionated Vite Starter Template' },
  ],
})

// 动态数据
const flowConfig = [
  { cname: '店铺账号', label: 'username', type: '0', value: 'loveebuyer', description: '请输入店铺账号' },
  { cname: '店铺密码', label: 'password', type: '0', value: 'zpf19870501.', description: '请输入店铺密码' },
  { cname: '登陆手机号', label: 'pthone', type: '0', value: '18626197197', description: '请输入登录手机号' },
  { cname: '支付密码', label: 'zfpassword', type: '0', value: '123456', description: '请输入支付密码' },
  { cname: '上传文件', label: 'data_file', type: '1', value: '', description: '请上传文件' },
]

interface AnyObject { }
// 表单绑定对象
const formData: AnyObject = reactive({})
// 初始化表单数据
flowConfig.forEach((v) => {
  formData[v.label] = v.value
})
// 表单提交
const handleSubmit = ({ values, errors }) => {
  console.log('values:', values, '\nerrors:', errors)
}

const font = new FontFace(
  '思源',
  `url(${location.href.split('#')[0]}src/assets/font.woff)`,
)
font.load().then(() => {
  document.fonts.add(font)
  document.body.classList.add('fonts-loaded')
})
</script>

<template>
  <!-- <img alt="Vue logo" src="./assets/logo.png" /> -->
  <!-- <HelloWorld msg="Hello Vue 3 + TypeScript + Vite" /> -->

  <p class="text-center flex items-center mb-4 test">
    <router-link class="inline-block px-4 py-2 my-2" :to="{ name: 'Home' }">Home</router-link>|
    <router-link class="inline-block px-4 py-2 my-2" :to="{ name: 'About' }">About</router-link>|
    <a class="inline-block px-4 py-2 my-2" href="/test">404</a>
  </p>

  <a-space class="flex justify-center">
    <a-button type="primary">Primary</a-button>
    <a-button>Secondary</a-button>
    <a-button type="dashed">Dashed</a-button>
    <a-button type="outline">Outline</a-button>
    <a-button type="text">Text</a-button>
  </a-space>

  <a-form class="m-10 w-600px" :model="formData" @submit="handleSubmit">
    <a-form-item
      v-for="(v, i) in flowConfig"
      :key="i"
      :field="v.label"
      :label="v.cname"
      :rules="[{ required: true, message: v.description }]"
    >
      <a-input v-model="formData[v.label]" :placeholder="v.description" />
    </a-form-item>
    <a-form-item>
      <a-button html-type="submit">Submit</a-button>
    </a-form-item>
  </a-form>
  {{ formData }}
  <router-view v-slot="{ Component, route }">
    <transition name="slide-fade" mode="out-in" appear>
      <component :is="Component" :key="route" />
    </transition>
  </router-view>
</template>

<style lang="scss" scoped>
// @font-face {
//   font-family: "思源";
//   src: url(~/assets/font.woff) format("woff");
// }
.test {
  color: #333;
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 1;
  font-family: "思源", "Arial", sans-serif;
}
</style>
