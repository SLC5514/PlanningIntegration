<!--
 * @Author: SLC
 * @Date: 2021-08-30 16:12:17
 * @LastEditors: SLC
 * @LastEditTime: 2021-11-05 09:50:36
 * @Description: file content
-->

# POP客户端

> Electron版本、实验阶段。

## 命令

```node
yarn            下载npm包
yarn start      本地启动
yarn make       electron-forge 打包
yarn builder    electron-builder 打包
yarn packager   electron-packager 打包
```

> 目前使用 `yarn make` + `Inno Setup` 打包

执行 `yarn make`

输出 `/out/pop-client-win32-x64`

使用 [`Inno Setup`](https://jrsoftware.org/isdl.php) 打包成 `exe` 安装包

更新根目录的 `exe` 安装包（备份至 `git`）
