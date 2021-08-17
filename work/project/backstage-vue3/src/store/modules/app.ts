/*
 * @Author: SLC
 * @Date: 2021-08-17 17:39:52
 * @LastEditors: SLC
 * @LastEditTime: 2021-08-17 17:46:08
 * @Description: file content
 */

const state = {
  loading: true
}
const mutations = {
  SET_LOADING: (state: { loading: boolean }) => {
    state.loading = false
  }
}
const actions = {
  setLoading({ commit }: any) {
    commit('SET_LOADING')
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
