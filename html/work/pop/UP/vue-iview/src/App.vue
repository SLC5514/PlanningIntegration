<template>
  <div id="app">
    <div id="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link> |
      <router-link to="/split">Split</router-link>
    </div>
    <router-view/>
  </div>
</template>

<style lang="less">
  html,body {
    width: 100%;
    height: 100%;
  }
</style>
