import Vue from 'vue'
import { Menu, MenuItem, locale, Page, Split, Slider } from 'iview'
import lang from 'iview/dist/locale/en-US'

locale(lang);

Vue.component('Page', Page)
Vue.component('Menu', Menu)
Vue.component('MenuItem', MenuItem)
Vue.component('Split', Split)
Vue.component('Slider', Slider)

import 'iview/dist/styles/iview.css'
import '../my-theme/index.less';