/* eslint-disable import/no-duplicates */

declare interface Window {
  // extend the window
}

declare interface FontFaceSet {
  add: Function
}

declare module '*.vue' {
  import { ComponentOptions } from 'vue'
  const component: ComponentOptions
  export default component
}
