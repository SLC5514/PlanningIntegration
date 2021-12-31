import commonjs from '@rollup/plugin-commonjs'
import { nodeResolve } from '@rollup/plugin-node-resolve'
import json from '@rollup/plugin-json'

export default {
  input: 'src/main.js',
  output: {
    // dir: 'dist',
    file: 'bundle.js',
    format: 'cjs'
  },
  plugins: [
    commonjs(),
    nodeResolve(),
    json()
  ]
}
