import json from "@rollup/plugin-json";
import commonjs from "@rollup/plugin-commonjs";
import typescript from "@rollup/plugin-typescript";
import eslint from "@rollup/plugin-eslint";
import { nodeResolve } from "@rollup/plugin-node-resolve";
import { babel } from "@rollup/plugin-babel";
import { terser } from "rollup-plugin-terser";

export default {
  external: ["fntk", "the-answer"],
  input: "src/main.js",
  output: [
    {
      dir: "dist",
      name: "rollupTest",
      format: "umd",
      entryFileNames: "entry-[name].js",
      globals: {
        "the-answer": "answer",
        fntk: "fntk",
      },
    },
    {
      dir: "dist",
      name: "rollupTest",
      format: "umd",
      entryFileNames: "entry-[name].min.js",
      globals: {
        "the-answer": "answer",
        fntk: "fntk",
      },
      plugins: [terser()],
    },
  ],
  plugins: [
    json(),
    eslint(),
    commonjs(),
    typescript({ lib: ["es5", "es6", "dom"], target: "es5" }),
    nodeResolve(),
    babel({
      exclude: "node_modules/**", // 只编译我们的源代码
      babelHelpers: "bundled",
    }),
  ],
};
