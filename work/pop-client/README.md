# POP客户端 V2.1

> Electron版本，试用阶段。

```node
yarn            下载npm包
yarn start      本地启动
yarn make       electron-forge 打包
```

执行 `yarn make`

输出 `out` 目录

> 可能用到的工具：[`Inno Setup`](https://jrsoftware.org/isdl.php)、[`Resource Hacker`](http://www.angusj.com/resourcehacker/)

1、推荐直接使用 `make` 目录中的安装包
  - Windows：
    - `\out\make\squirrel.windows\x64\POP客户端-2.1.0 Setup.exe` 需要使用 `Resource Hacker` 替换ico图标（写配置后打包会报错，暂无头绪，故手动修改）
  - Mac：
    - `\out\make\POP客户端-2.1.0-x64.dmg` dmg版本
    - `\out\make\zip\darwin\x64\POP客户端-darwin-x64-2.1.0.zip` app版本

> 注意：Mac中会出现无读写权限导致报错，待修复；外快捷键无响应，待修复

2、Windows：`\out\POP客户端-win32-x64` 目录
  - 使用 [`Inno Setup`](https://jrsoftware.org/isdl.php) 打包此目录成 `exe` 安装包（参考：[打包安装文件](https://www.cnblogs.com/kakayang/p/9559777.html)）

其他：

修改 `package.json` 中的 `version` 字段作为版本号

> 注意：需要兼容 SemVer version number 格式 如 `1.0.0`
