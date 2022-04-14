<script lang="ts" setup>
</script>

<template>
  <div class="navbar h-15 leading-2.1875rem px-6 py-3 border-b-1 border-solid border-[#eaecef]">
    <router-link class="logo-link inline-block font-bold" :to="{ name: 'Home' }">
      Home
    </router-link>
    <div class="navbar-links-wrapper float-right">
      <router-link class="inline-block" :to="{ name: 'Home' }">
        Home
      </router-link>
      <span class="mx-5">|</span>
      <router-link class="inline-block" :to="{ name: 'Home' }">
        Home
      </router-link>
      <span class="mx-5">|</span>
      <router-link class="inline-block" :to="{ name: 'Home' }">
        Home
      </router-link>
    </div>
  </div>
</template>
