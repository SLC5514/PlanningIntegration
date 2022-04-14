/*
 * @Author: SLC
 * @Date: 2021-08-17 17:39:52
 * @LastEditors: SLC
 * @LastEditTime: 2021-08-18 09:31:08
 * @Description: file content
 */

const state = {
  loading: true
}
const mutations = {
  SET_LOADING: (state: { loading: boolean }, data: boolean) => {
    state.loading = data
  }
}
const actions = {
  setLoading({ commit }: any, data: boolean) {
    commit('SET_LOADING', data)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
