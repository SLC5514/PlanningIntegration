/*
 * @Author: SLC
 * @Date: 2021-07-19 09:20:11
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-19 15:31:36
 * @Description: file content
 */

const { series, parallel, src, dest, watch } = require("gulp");

const gulpif = require("gulp-if");
const notify = require("gulp-notify");
const concat = require("gulp-concat");
const uglify = require("gulp-uglify");
const rename = require("gulp-rename");
const babel = require("gulp-babel");
const sass = require("gulp-sass")(require("sass"));
const sourcemaps = require("gulp-sourcemaps");
const autoprefixer = require("gulp-autoprefixer");

const del = require("delete");
// const ts = require("gulp-ts");

// 清空输出目录
function clearOutput(path: String | Array<String>, next: Function) {
  del(path, next);
}
// 判断js后缀
function isJavaScript(file: any) {
  return file.extname === ".js";
}

// 判断ts后缀
function isTypeScript(file: any) {
  return file.extname === ".ts";
}

// 判断scss后缀
function isScss(file: any) {
  return file.extname === ".scss";
}

// 判断css后缀
function isCss(file: any) {
  return file.extname === ".css";
}

// 清空任务
function clearTask(next: any) {
  del(["maps/**/*", "output/**/*"], (err: Error, deleted: Array<String>) => {
    if (err) throw err;
    console.log("Del Success:", deleted);
    next();
  });
}

// js任务
function jsTask(next: any) {
  src(["src/**/*.js", "src/**/*.ts"])
    // .pipe(sourcemaps.init()) // 源映射
    .pipe(
      gulpif(
        isJavaScript,
        babel({
          presets: ["@babel/env"], // babel编译
        })
      )
    )
    .pipe(gulpif(isJavaScript, concat("all.js"))) // 拼接文件
    .pipe(uglify()) // 压缩文件
    .pipe(rename({ extname: ".min.js" })) // 重命名
    // .pipe(sourcemaps.write('../maps')) // 源映射
    .pipe(dest("output/"));
  next();
}

// css任务
function cssTask(next: any) {
  src(["src/**/*.css", "src/**/*.scss"])
    .pipe(sourcemaps.init()) // 源映射
    .pipe(gulpif(isScss, sass().on("error", sass.logError))) // sass编译
    .pipe(autoprefixer()) // 添加样式前缀
    .pipe(sourcemaps.write("../maps")) // 源映射
    .pipe(dest("output/"));
  next();
}

// 默认任务
function defaultTask() {
  clearOutput("output/**/*", (err: Error, deleted: Array<String>) => {
    if (err) throw err;
    console.log("Del Success:", deleted);
  });
  return (
    src("src/**/*")
      // .pipe(sourcemaps.init()) // 源映射
      .pipe(
        gulpif(
          isJavaScript,
          babel({
            presets: ["@babel/env"], // babel编译
          })
        )
      )
      .pipe(gulpif(isJavaScript, concat("all.js"))) // 拼接文件
      .pipe(
        gulpif(
          (file: any) => isJavaScript(file) || isTypeScript(file),
          uglify()
        )
      ) // 压缩文件
      .pipe(
        gulpif(
          (file: any) => isJavaScript(file) || isTypeScript(file),
          rename({ extname: ".min.js" }) // 重命名
        )
      )
      .pipe(gulpif((file: any) => isCss(file) || isScss(file), autoprefixer())) // 添加样式前缀
      .pipe(gulpif(isScss, sass().on("error", sass.logError))) // sass编译
      // .pipe(sourcemaps.write('../maps')) // 源映射
      .pipe(dest("output/"))
  ); // 输出
  // .pipe(notify("完成!")); // notify 通知
}

// if (process.env.NODE_ENV === "production") {
//   exports.build = series(transpile, minify);
// } else {
//   exports.build = series(transpile, livereload);
// }

// exports.build = series(
//   clean,
//   parallel(cssTranspile, series(jsTranspile, jsBundle)),
//   parallel(cssMinify, jsMinify),
//   publish
// );

// watch("src/**/*", defaultTask);

exports.build = series(clearTask, parallel(jsTask, cssTask));
exports.default = defaultTask;
