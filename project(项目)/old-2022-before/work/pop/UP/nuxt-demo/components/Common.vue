<template>
  <section class="container">
    <div>
      <Logo />
      <h1 class="title">
        <slot name="title" />
      </h1>
      <h2 class="subtitle">
        <slot name="subtitle" />
      </h2>
      <div class="links">
        <nuxt-link
          class="button--green"
          to="/"
        >
          Home
        </nuxt-link>
        <nuxt-link
          class="button--grey"
          :to="'/about' + (
            (params.name ? '/' + params.name : '') +
            (params.id ? '/' + params.id : '')
          )"
        >
          About
        </nuxt-link>
        <nuxt-link
          class="button--grey"
          to="/feedback"
        >
          Feedback
        </nuxt-link>
        <nuxt-link
          class="button--grey"
          to="/users"
        >
          Users
        </nuxt-link>
        <nuxt-link
          class="button--grey"
          to="/users/456"
        >
          UsersId
        </nuxt-link>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'Common',
  components: {
    Logo: () => import('~/components/Logo')
  },
  props: {
    params: {
      type: Object,
      default: () => {
        return {}
      }
    }
  }
}
</script>

<style scoped>
/* 不用less嵌套以防止权重过高 */
.container {
  margin: 0 auto;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.title {
  font-family: 'Quicksand', 'Source Sans Pro', -apple-system, BlinkMacSystemFont,
    'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  display: block;
  font-weight: 300;
  font-size: 100px;
  color: #35495e;
  letter-spacing: 1px;
}

.subtitle {
  font-weight: 300;
  font-size: 42px;
  color: #526488;
  word-spacing: 5px;
  padding-bottom: 15px;
}

.links {
  padding-top: 15px;
}

.button--green {
  display: inline-block;
  border-radius: 4px;
  border: 1px solid #3b8070;
  color: #3b8070;
  text-decoration: none;
  padding: 10px 30px;
}

.button--green:hover {
  color: #fff;
  background-color: #3b8070;
}

.button--grey {
  display: inline-block;
  border-radius: 4px;
  border: 1px solid #35495e;
  color: #35495e;
  text-decoration: none;
  padding: 10px 30px;
  margin-left: 15px;
}

.button--grey:hover {
  color: #fff;
  background-color: #35495e;
}
</style>
