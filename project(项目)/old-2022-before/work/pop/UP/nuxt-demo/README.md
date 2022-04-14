# Nuxt-demo

> A nuxt.js demo

## Build Setup

```bash
# 安装依赖
$ npm install

# 在本地3000端口上启动热更新服务
$ npm run dev

# 为了生产和启动服务器而构建
$ npm run build
$ npm start

# 生成静态项目
$ npm run generate
```

> 有关事物如何工作的详细说明，请前往 [Nuxt.js](https://nuxtjs.org).  
> 项目地址: [Nuxt-demo](http://101.132.178.141/).  
> _本文档截止于 `2019-01-31`_.

---

# nuxt 简易教程

> `Nuxt.js` 是一个基于 `Vue.js` 的通用应用框架。

## 安装

使用 Nuxt.js 团队脚手架工具  
确保安装了 `npx` (npx 在 NPM 版本 5.2.0 默认安装了)

```bash
npx create-nuxt-app <项目名>
```

它会让你进行一些选择:

1. 设置项目`名称`

2. 设置项目`描述`

3. 选择集成的`服务器端框架`

4. 选择需要安装的`特性` (使用`空格键`多选，axios、eslint...)

5. 选择你喜欢的 `UI 框架`

6. 选择`测试框架`

7. 选择你想要的 Nuxt `模式` (`Universal` or `SPA`)

8. 设置`作者`

## 目录结构

- **`assets`** 资源目录：用于组织`未编译`的`静态资源`如 LESS、SASS 或 JavaScript。

- **`components`** 组件目录：用于组织应用的 `Vue.js 组件`。

- **`layouts`** 布局目录：用于组织应用的`布局`组件。

- **`middleware`** 中间件目录：用于存放应用的`中间件`。

- **`pages`** 页面目录：用于组织应用的`路由及视图`。

- **`plugins`** 插件目录：用于组织那些需要在 根 vue.js 应用 `实例化之前`需要运行的 `Javascript 插件`。

- **`static`** 静态文件目录：用于存放应用的`静态文件`，不会进行`构建编译`处理，服务器启动时，该目录下的文件会映射至应用的`根路径 /` 下。

- **`store`** 状态树目录：用于组织应用的 `Vuex 状态树` 文件。

- **`nuxt.config.js`** 文件：用于组织 Nuxt.js 应用的个性化`配置`。

- **`package.json`** 文件：用于描述应用的`依赖`关系和对外暴露的`脚本`接口。

## 别名

| 别名         | 目录     |
| ------------ | -------- |
| `~` 或 `@`   | src 目录 |
| `~~` 或 `@@` | 根目录   |

默认情况下，src 目录和根目录相同

_提示 : 在你的 `vue 模板`中, 如果你需要引入 `assets` 或者 `static` 目录, 使用 `~/assets/your_image.png` 和 `~/static/your_image.png` 方式。_

---

## nuxt.config.js 配置属性

> Nuxt.js 默认的配置涵盖了大部分使用情形，可通过 `nuxt.config.js` 来覆盖默认的配置。

```javascript
module.exports = {
  /*
  ** 模式：spa (单页应用) | universal (通用应用)
  */
  mode: 'universal',

  /*
  ** 页面head (设置title、meta、link等)
  */
  head: {},

  /*
  ** 自定义进度条颜色
  */
  loading: { color: '#fff' },

  /*
  ** 全局 CSS
  */
  css: [],

  /*
  ** 配置需要在 根vue.js应用 实例化之前需要运行的 Javascript 插件
  */
  plugins: [],

  /*
  ** Nuxt.js 模块
  */
  modules: [],

  /*
  ** Axios 模块配置
  */
  axios: {},

  /*
  ** 构建配置
  */
  build: {}

  /*
  ** 设置 Nuxt.js 应用的根目录
  */
  // rootDir: '',

  /*
  ** 设置 Nuxt.js 应用的源码目录
  */
  // srcDir: '',

  /*
  ** 缓存配置
  */
  // cache: true,

  /*
  ** 配置 Nuxt.js 应用是开发模式还是生产模式
  ** dev 属性的值会被 nuxt 命令 覆盖
  */
  // dev: process.env.NODE_ENV !== 'production',

  /*
  ** 可以配置在客户端和服务端共享的环境变量
  */
  // env: {
  //   baseUrl: process.env.BASE_URL || 'http://localhost:3000'
  // },

  /*
  ** 配置 Nuxt.js 应用生成静态站点的具体方式
  */
  // generate: {},

  /*
  ** 自定义Nuxt.js应用程序的node_modules文件夹
  */
  // modulesDir: ['node_modules'],

  /*
  ** 个性化配置 Nuxt.js 应用的路由
  */
  // router: {},

  /*
  ** 设置页面切换过渡效果的默认属性值
  */
  // transition: '',
}
```

---

## 路由

> Nuxt.js 依据 pages 目录结构自动生成 vue-router 模块的路由配置。

_要在页面之间跳转路由，我们建议使用 `<nuxt-link>` 标签 (与 `<router-link>` 一致)。_

### `基础路由`

```javascript
pages/
--| user/
-----| index.vue
-----| one.vue
--| index.vue

url:

》 /
》 /user
》 /user/one
```

### `动态路由`

> 需要创建对应的以 **`下划线`** 作为前缀的 Vue 文件 或 目录。

```javascript
pages/
--| _slug/
-----| comments.vue
-----| index.vue
--| users/
-----| _id.vue
--| index.vue

url:

》 /
》 /user/:id?
》 /:slug
》 /:slug/comments
```

:id`?` 表示该路由是`可选的`，想将它设置为`必选`的路由，需要在 `users/\_id 目录`内创建一个 `index.vue` 文件。

```javascript
users/
--| _id/
-----| index.vue
```

_警告：`generate` 命令会`忽略`动态路由: [API Configuration generate](https://zh.nuxtjs.org/api/configuration-generate/#routes)_

### `路由参数校验`

> Nuxt.js 可以让你在`动态路由`组件中定义参数校验方法。

```javascript
// pages/users/_id.vue

export default {
  validate({ params }) {
    // 必须是number类型
    return /^\d+$/.test(params.id)
  }
}
```

_如果校验方法返回的值不为 `true` 或 Promise 中 resolve 解析为 `false` 或抛出 `Error` ， Nuxt.js 将自动加载显示 `404` 错误页面或 `500` 错误页面。_

### `嵌套路由`

> 需要添加一个 Vue 文件，同时添加一个与该文件`同名`的`目录`用来存放子视图组件

**Warning : 别忘了在`父组件`(.vue 文件) 内增加 `<nuxt-child/>` 用于显示子视图内容。**

```javascript
pages/
--| users/
-----| _id.vue
-----| index.vue
--| users.vue   (父组件)

url:

》 /users
》 /users/:id

create:

routes: [
  {
    path: '/users',
    component: 'pages/users.vue',
    children: [
      {
        path: '',
        component: 'pages/users/index.vue',
        name: 'users'
      },
      {
        path: ':id',
        component: 'pages/users/_id.vue',
        name: 'users-id'
      }
    ]
  }
]
```

### `动态嵌套路由`

```javascript
pages/
--| _category/
-----| _subCategory/
--------| _id.vue
--------| index.vue
-----| _subCategory.vue
-----| index.vue
--| _category.vue
--| index.vue

url:

》 /
》 /:category
》 /:category/:subCategory
》 /:category/:subCategory/:id

create:

routes: [
  {
    path: '/',
    component: 'pages/index.vue'
  },
  {
    path: '/:category',
    component: 'pages/_category.vue',
    children: [
      {
        path: '',
        component: 'pages/_category/index.vue'
      },
      {
        path: '/:subCategory',
        component: 'pages/_category/_subCategory.vue',
        children: [
          {
            path: '',
            component: 'pages/_category/_subCategory/index.vue'
          },
          {
            path: '/:id',
            component: 'pages/_category/_subCategory/_id.vue'
          }
        ]
      }
    ]
  }
]
```

### `过渡动效`

> Nuxt.js 使用 Vue.js 的 `<transition>` 组件来实现路由切换时的过渡动效。

#### 全局过渡动效设置

_提示 : Nuxt.js 默认使用的过渡效果名称为 `page`_

```css
/* assets/main.css */

.page-enter-active,
.page-leave-active {
  transition: opacity 0.5s;
}
.page-enter,
.page-leave-active {
  opacity: 0;
}
```

然后添加到 nuxt.config.js 文件中：

```javascript
// nuxt.config.js

module.exports = {
  css: ['assets/main.css']
}
```

_关于过渡效果 transition 属性配置的更多信息可参看 [页面过渡效果 API](https://zh.nuxtjs.org/api/pages-transition/)。_

#### 页面过渡动效设置

```javascript
// page/users.vue

export default {
  transition: 'test'
}
```

```css
.test-enter-active,
.test-leave-active {
  transition: opacity 0.5s;
}
.test-enter,
.test-leave-active {
  opacity: 0;
}
```

### `中间件`

> 允许你定义一个自定义函数运行在一个页面或一组页面渲染之前。

_文件名的名称将成为中间件名称 (`middleware/auth.js` 将成为 `auth` 中间件)_

一个中间件接收 `context` 作为第一个参数：

```javascript
// middleware/auth.js

export default function(context) {
  context.userAgent = process.server
    ? context.req.headers['user-agent']
    : navigator.userAgent
}
```

`异步执行`只需要返回一个 `Promise` 或使用第 2 个 `callback` 作为第一个参数：

```javascript
// middleware/stats.js

import axios from 'axios'

export default function({ route }) {
  return axios.post('http://my-stats-api.com', {
    url: route.fullPath
  })
}
```

在你的 nuxt.config.js 、 layouts 或者 pages 中使用中间件:

```javascript
// nuxt.config.js

module.exports = {
  router: {
    middleware: 'stats'
  }
}
```

---

## 视图

> 为指定的路由配置数据和视图，包括应用`模板`、`页面`、`布局`和 HTML `头部`等内容。

### `模板`

定制化默认的 html 模板，只需要在应用`根目录`下创建一个 `app.html` 的文件。

默认模板为：

```html
<!DOCTYPE html>
<html {{ HTML_ATTRS }}>
  <head>
    {{ HEAD }}
  </head>
  <body {{ BODY_ATTRS }}>
    {{ APP }}
  </body>
</html>
```

举个例子，你可以修改模板添加 IE 的条件表达式：

```html
<!DOCTYPE html>
<!--[if IE 9]><html lang="en-US" class="lt-ie9 ie9" {{ HTML_ATTRS }}><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html {{ HTML_ATTRS }}><!--<![endif]-->
  <head>
    {{ HEAD }}
  </head>
  <body {{ BODY_ATTRS }}>
    {{ APP }}
  </body>
</html>
```

### `布局`

> Nuxt.js 允许你扩展默认的布局，或在 layout 目录下创建自定义的布局。

_别忘了在布局文件中添加 `<nuxt/>` 组件用于显示页面的主体内容。_

#### 默认布局

_可通过添加 `layouts/default.vue` 文件来扩展应用的默认布局。_

默认布局的源码如下：

```html
<!-- layouts/default.vue -->

<template>
  <nuxt/>
</template>
```

#### 个性化布局

举个例子:

```html
<!-- layouts/blog.vue -->

<template>
  <div>
    <div>这里是博客导航</div>
    <nuxt/>
  </div>
</template>
```

在页面中使用 blog 布局:

```html
<!-- pages/posts.vue -->

<script>
export default {
  layout: 'blog'
}
</script>
```

#### 错误页面

_可以通过编辑 `layouts/error.vue` 文件来定制化错误页面._

不需要包含 `<nuxt/>` 标签。你可以把这个布局文件当成是显示应用错误（`404`，`500` 等）的组件。

举一个个性化错误页面的例子:

```html
<!-- layouts/error.vue -->

<template>
  <div class="container">
    <h1 v-if="error.statusCode === 404">页面不存在</h1>
    <h1 v-else>应用发生错误异常</h1>
    <nuxt-link to="/">首 页</nuxt-link>
  </div>
</template>

<script>
export default {
  props: {
    error: {
      type: Object,
      default: () => {
        return {}
      }
    }
  },
  layout: 'blog' // 你可以为错误页面指定自定义的布局
}
</script>
```

### `页面`

_页面组件实际上是 `Vue 组件`，只不过 Nuxt.js 为这些组件添加了一些特殊的`配置项`（对应 Nuxt.js 提供的功能特性）以便你能快速开发通用应用。_

```html
<template>
  <h1 class="red">Hello {{ name }}!</h1>
</template>

<script>
export default {
  asyncData (context) {
    // 异步数据处理 每次在加载组件之前调用
    return { name: 'World' }
  },
  fetch () {
    // 用于在呈现页面之前填充状态树
  },
  head: {}, // 为页面设置head
  // 更多
  ...
}
</script>

<style>
.red {
  color: red;
}
</style>
```

Nuxt.js 为页面提供的特殊配置项：

| 属性名        | 描述                                                                                                                                                                              |
| ------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `asyncData`   | 支持 `异步数据处理`，第一个参数为当前页面组件的 `上下文对象`。                                                                                                                    |
| `fetch`       | 用于在 `渲染页面之前` 获取数据填充应用的 `状态树（store）`。不同的是 fetch 方法不会设置 `组件的数据`（data）。                                                                    |
| `head`        | 配置当前页面的 `Meta 标签`。                                                                                                                                                      |
| `layout`      | 指定当前页面使用的 `布局`（layouts 根目录下的布局文件）。                                                                                                                         |
| `loading`     | 如果设置为 `false`，则阻止页面自动调用 `this.$nuxt.$loading.finish()` 和 `this.$nuxt.$loading.start()`, 你可以手动控制它, 仅适用于在 `nuxt.config.js` 中设置 `loading` 的情况下。 |
| `transition`  | 指定页面切换的 `过渡动效`。                                                                                                                                                       |
| `scrollToTop` | 布尔值，默认: `false`。 用于判定 `渲染页面前` 是否需要将当前页面 `滚动至顶部`。这个配置用于 `嵌套路由` 的应用场景。                                                               |
| `validate`    | 校验方法用于校验 `动态路由的参数`。                                                                                                                                               |
| `middleware`  | 指定页面的`中间件`，中间件会在页面`渲染之前`被调用。                                                                                                                              |

---

### `asyncData`

> asyncData 方法会在组件（`限于页面组件`）每次加载之前被调用。

_注意：由于 `asyncData` 方法是在组件 `初始化` 前被调用的，所以在方法内是没有办法通过 `this` 来引用组件的实例对象。_

返回 `Promise` :

```javascript
export default {
  asyncData({ params }) {
    return axios.get(`https://my-api/posts/${params.id}`).then(res => {
      return { title: res.data.title }
    })
  }
}
```

使用 `async` 或 `await` :

```javascript
export default {
  async asyncData({ params }) {
    let { data } = await axios.get(`https://my-api/posts/${params.id}`)
    return { title: data.title }
  }
}
```

使用 `回调函数` :

```javascript
export default {
  asyncData({ params }, callback) {
    axios.get(`https://my-api/posts/${params.id}`).then(res => {
      callback(null, { title: res.data.title })
    })
  }
}
```

### `head`

一个使用自定义 viewport 和 谷歌字体 的配置示例：

```javascript
head: {
  meta: [
    { charset: 'utf-8' },
    { name: 'viewport', content: 'width=device-width, initial-scale=1' },
    { hid: 'description', name: 'description', content: 'A about-name-id page' }
  ],
  link: [
    { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Roboto' }
  ]
}
```

_注意：为了避免子组件中的 meta 标签不能正确`覆盖`父组件中相同的标签而产生重复的现象，建议利用 `hid` 键为 meta 标签配一个唯一的标识编号。_

---

## 资源文件

> 默认使用 `vue-loader`、`file-loader` 以及 `url-loader` 这几个 Webpack 加载器来处理文件的加载和引用。  
> 对于不需要通过 Webpack 处理的静态资源文件，可以放置在 `static` 目录中。

### `Webpack 构建`

_请注意: 从 Nuxt 2.0 开始，〜/alias 将无法在 CSS 文件中正确解析。你必须在 `url CSS` 引用中使用 `~assets`（没有斜杠）或 `@` 别名，即 background: url("`~assets/banner.svg`")_

在 CSS 代码中使用 url('`~assets`/image.png')

在 vue 模板中:

```html
<template>
  <img src="~/assets/image.png">
</template>
```

### `静态文件`

> `static` 目录下的文件会映射至应用的根路径 / 下。

可以在代码中使用`根路径 /` 结合资源相对路径来引用静态资源：

```html
<!-- 引用 static 目录下的图片 -->
<img src="/my-image.png"/>

<!-- 引用 assets 目录下经过 webpack 构建处理后的图片 -->
<img src="/assets/my-image-2.png"/>
```

---

## Vuex 状态树

> 对于每个大项目来说，使用`状态树 (store)` 管理状态 (`state`) 十分有必要。

### `使用状态树`

Nuxt.js 会尝试找到应用根目录下的 `store 目录`，如果该目录存在，它将做以下的事情：

1. 引用 `vuex` 模块

2. 将 `vuex` 模块 加到 `vendors` 构建配置中去

3. 设置 `Vue` 根实例的 `store` 配置项

Nuxt.js 支持两种使用 store 的方式，你可以择一使用：

- `普通`方式： `store/index.js` 返回一个 `Vuex.Store` 实例

- `模块`方式： `store` 目录下的每个 `.js` 文件会被转换成为状态树指定命名的子模块 （当然，`index` 是根模块）

#### 普通方式

使用普通方式的状态树，需要添加 `store/index.js` 文件，并对外暴露一个 `Vuex.Store` 实例：

```javascript
// store/index.js

import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = () =>
  new Vuex.Store({
    state: {
      counter: 0
    },
    mutations: {
      increment(state) {
        state.counter++
      }
    }
  })

export default store
```

_`Nuxt.js` 内置引用了 `vuex` 模块，所以不需要额外安装。_

现在我们可以在组件里面通过 `this.$store` 来使用状态树：

```html
<template>
  <button @click="$store.commit('increment')">{{ $store.state.counter }}</button>
</template>
```

#### 模块方式

_状态树还可以拆分成为模块，`store` 目录下的每个 `.js` 文件会被转换成为状态树指定命名的子模块_

`store/index.js` 应该直接将 `state`、`mutations` 和 `actions` 暴露出来：

```javascript
export const state = () => ({
  counter: 0
})

export const mutations = {
  increment(state) {
    state.counter++
  }
}
```

模块文件

_你可以将模块文件分解为单独的文件：`state.js`, `actions.js`, `mutations.js` 和 `getters.js`。_

```javascript
// store/state.js

export default () => ({
  counter: 0
})
```

```javascript
// store/mutations.js
export default {
  increment(state) {
    state.counter++
  }
}
```

你可以在页面中这样使用：

```html
<template>
  <ul>
    <li>
      {{counterNum}}
    </li>
    <li>
      <button @click="increment">add</button>
    </li>
  </ul>
</template>

<script>
import { mapMutations } from 'vuex'

export default {
  computed: {
    counterNum () { return this.$store.state.counter }
  },
  methods: {
    ...mapMutations({
      increment: 'increment'
    })
  }
}
</script>
```

---

## 发布部署

> `Nuxt.js` 提供了两种发布部署应用的方式：`服务端渲染应用部署` 和 `静态应用部署`。

### `服务端渲染应用部署`

应该先进行编译构建，然后再启动 Nuxt 服务:

```bash
nuxt build
nuxt start
```

推荐的 package.json 配置如下：

```json
{
  "name": "my-app",
  "dependencies": {
    "nuxt": "latest"
  },
  "scripts": {
    "dev": "nuxt",
    "build": "nuxt build",
    "start": "nuxt start"
  }
}
```

_提示：建议将 `.nuxt` 加入 `.npmignore` 和 `.gitignore` 文件中。_

### `静态应用部署`

可利用下面的命令生成应用的静态目录和文件：

```bash
npm run generate
```

这个命令会创建一个 `dist` 文件夹，所有静态化后的资源文件均在其中。

_如果你的项目需要用到`动态路由`，请移步 [generate 配置 API](https://zh.nuxtjs.org/api/configuration-generate) 了解如何让 `Nuxt.js` 生成此类动态路由的静态文件。_

---

> 想了解更多请查看 [Nuxt.js](https://zh.nuxtjs.org/guide).
