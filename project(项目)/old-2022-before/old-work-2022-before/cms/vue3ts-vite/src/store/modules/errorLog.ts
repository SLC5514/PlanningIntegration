import { Module } from "vuex";

interface StoreUser {
  logs: Array<AnyObject>;
}

const store: Module<StoreUser, unknown> = {
  namespaced: true,
  state() {
    return {
      logs: []
    };
  },
  mutations: {
    ADD_ERROR_LOG: (state: StoreUser, log) => {
      state.logs.push(log)
    },
    CLEAR_ERROR_LOG: (state: StoreUser) => {
      state.logs.splice(0)
    }
  },
  actions: {
    addErrorLog({ commit }, log) {
      commit('ADD_ERROR_LOG', log)
    },
    clearErrorLog({ commit }) {
      commit('CLEAR_ERROR_LOG')
    }
  },
};

export default store;
