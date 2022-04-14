# POP客户端 V2.0

> Electron版本，试用阶段。

```node
yarn            下载npm包
yarn start      本地启动
yarn make       electron-forge 打包
yarn builder    electron-builder 打包
yarn packager   electron-packager 打包
```

> 目前使用 `yarn make` + `Inno Setup` 打包

执行 `yarn make`

输出 `/out/pop-client-win32-x64/`

使用 [`Inno Setup`](https://jrsoftware.org/isdl.php) 打包成 `exe` 安装包（参考：[打包安装文件](https://www.cnblogs.com/kakayang/p/9559777.html)）

更新根目录的 `exe` 安装包（备份至 `git`）

其他：

修改 `package.json` 中的 `version` 字段作为版本号

> 注意：需要兼容 SemVer version number 格式

> 注意：将输出目录及其下 `\resources\app` 目录下的 `POP客户端.exe`、`yarn.lock`、`db.json` 等文件删除，减小打包体积
