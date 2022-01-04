import json from "@rollup/plugin-json";
import commonjs from "@rollup/plugin-commonjs";
import typescript from "@rollup/plugin-typescript";
import { nodeResolve } from "@rollup/plugin-node-resolve";
import { babel } from "@rollup/plugin-babel";

export default {
  input: "src/main.js",
  output: {
    file: "bundle.js",
    format: "cjs",
  },
  plugins: [
    json(),
    commonjs(),
    typescript({ lib: ["es5", "es6", "dom"], target: "es5" }),
    nodeResolve(),
    babel({
      exclude: "node_modules/**", // 只编译我们的源代码
      babelHelpers: "bundled",
    }),
  ],
  // 指出应将哪些模块视为外部模块
  external: ["fntk"],
};
