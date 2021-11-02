import { Module } from "vuex";

// import variables from '~/styles/element-variables.scss'
import defaultSettings from "~/settings";

const { showSettings, tagsView, fixedHeader, sidebarLogo } = defaultSettings;

interface StoreUser {
  theme: string; // variables.theme,
  showSettings: boolean;
  tagsView: boolean;
  fixedHeader: boolean;
  sidebarLogo: boolean;
}

const store: Module<StoreUser, unknown> = {
  namespaced: true,
  state() {
    return {
      theme: "", // variables.theme,
      showSettings: showSettings,
      tagsView: tagsView,
      fixedHeader: fixedHeader,
      sidebarLogo: sidebarLogo,
    };
  },
  mutations: {
    CHANGE_SETTING(
      state: StoreUser,
      { key, value }: { key: keyof StoreUser; value: never }
    ) {
      // eslint-disable-next-line no-prototype-builtins
      if (state.hasOwnProperty(key)) {
        state[key] = value;
      }
    },
  },
  actions: {
    changeSetting({ commit }, data: AnyObject) {
      commit("CHANGE_SETTING", data);
    },
  },
};

export default store;
