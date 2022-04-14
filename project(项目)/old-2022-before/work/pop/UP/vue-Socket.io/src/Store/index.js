import Vue from 'vue'
import vuex from 'vuex'

Vue.use(vuex)

export default new vuex.Store({
  state: {
    users: {
      id: '',
      list: [],
      msg: []
    }
  },
  getters: {},
  mutations: {
    saveUsers (state, val) {
      state.users = val
    }
  },
  actions: {
    saveUsers ({ commit }, val) {
      commit('saveUsers', val)
    }
  }
})
