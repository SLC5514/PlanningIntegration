const Home = {
  template: `
    <div class="page has-navbar" v-nav="{ title: 'Home' }">
      <div class="page-content text-center">
        <h2 class="padding" v-text="msg"></h2>
        <router-link class="button button-assertive" to="/about">
          <i class="ion-information-circled"></i> About
        </router-link>
        <md-button class="button button-balanced button-block" @click.native="onClick()">
          show toast
        </md-button>
      </div>
    </div>
  `,
  data() {
    return {
      msg: 'Hello! Vonic.'
    }
  }
}

function onClick() {
  $toast.show("button clicked.")
}