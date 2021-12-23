export default {
  namespaced: true,
  state: () => ({
    name: 'test',
  }),
  getters: {
    getName: state => state.name,
  },
  mutations: {
    setName(state, name) {
      state.name = name
    },
  },
}
