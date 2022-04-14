# POP客户端 V2.0.3

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
  - ~~Windows：~~
    - `\out\make\squirrel.windows\x64\POP客户端-2.1.0 Setup.exe` 需要使用 `Resource Hacker` 替换ico图标（写配置后打包会报错，暂无头绪，故手动修改）
  - ~~Mac：~~
    - `\out\make\POP客户端-2.1.0-x64.dmg` dmg版本
    - `\out\make\zip\darwin\x64\POP客户端-darwin-x64-2.1.0.zip` app版本

> 注意：Mac中会出现无读写权限导致报错，待修复；快捷键无响应，待修复

2、Windows：`\out\POP客户端-win32-x64` 目录
  - 修改 `POP客户端.exe` 的权限
    - 使用 [`Resource Hacker`](http://www.angusj.com/resourcehacker/) 打开 `exe` 程序
    - 将 `<requestedExecutionLevel level="asInvoker" uiAccess="false"/></requestedPrivileges>` 改为 `<requestedExecutionLevel level="requireAdministrator" uiAccess="false"/></requestedPrivileges>` 后 编译 保存
  - 使用 [`Inno Setup`](https://jrsoftware.org/isdl.php) 打包此目录成 `exe` 安装包（参考：[打包安装文件](https://www.cnblogs.com/kakayang/p/9559777.html)）
    - `Application name`（App 名称）
      - POP客户端
    - `Application version`（App 版本号）
      - x.x.x
    - `Application publisher`（App 作者）
      - POP, Inc.
    - `Application website`（App 网址）
      - http://www.pop136.com/
    - `Application main executable file`（App 主程序）
      - to/path/xxx.exe
    - `Other application files`（App 目录）
      - to/path/
    -  `Application file type name`（App 文件类型名称）
      - POP客户端
    - `Application file type extension`（App 文件类型扩展名）
      - .exe
    - `Languages`（语言）
      - [Chinese Simplified](https://raw.githubusercontent.com/jrsoftware/issrc/main/Files/Languages/Unofficial/ChineseSimplified.isl)
    - `Complier output base file name`（安装包名称）
      - POP客户端x64 Vx.x.x 或 POP客户端x86 Vx.x.x
    - `Custom Setup icon file`（安装包图标文件）
      - to/path/favicon.ico
  - 修改打包后 `exe` 程序的权限，参考上方

其他：

修改 `package.json` 中的 `version` 字段作为版本号

复制 `db.json` 至安装包程序根目录

> 注意：需要兼容 SemVer version number 格式 如 `1.0.0`
