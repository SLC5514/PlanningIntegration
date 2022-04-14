<script setup lang="ts">
import { addUser } from './api/test';

console.log(addUser)
// const addUserAwait = async () => {
//   const res = await addUser();
//   console.log(res);
// }
// addUserAwait()

</script>

<template>
  <div class="self-box">
    <div>增</div>
    <ul>
      <li>添加用户: username password email</li>
    </ul>
    <div>删</div>
    <ul>
      <li>删除用户</li>
    </ul>
    <div>改</div>
    <ul>
      <li>修改密码</li>
    </ul>
    <div>查</div>
    <ul>
      <li>查询用户</li>
    </ul>
  </div>
</template>

<style lang="scss">
.self-box {
  margin: 0 auto;
}
</style>