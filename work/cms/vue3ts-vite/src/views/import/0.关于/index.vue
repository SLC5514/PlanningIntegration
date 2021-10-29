<script lang="ts" setup>
import { ref } from 'vue';
const count = ref(0);
</script>

<template>
  <section id="active-container">
    <h1>关于</h1>{{count}}<button @click="count++">add</button>
    <p>目录规则：序号.标题</p>
    <section>
      <h2>已实现</h2>
      <ul>
        <li>页面自动导入路由</li>
        <li>路由自动生成菜单</li>
        <li>动态title <mark>问题</mark>：自动导入页面的标题如何归置 <mark>方案</mark>：使用目录名</li>
        <li>面包屑导航</li>
        <li>页面过渡 <mark>*</mark>：需要有根节点</li>
        <li>标签导航</li>
        <li>项目加载&进度</li>
      </ul>
    </section>
    <section>
      <h2>待实现</h2>
      <ul>
        <li>路由权限 <mark>*</mark>：须配合账号登陆系统</li>
        <li>列表过渡</li>
        <li>页面缓存 <mark>*</mark>：进行中</li>
        <li>全局动态配置</li>
        <li>错误日志</li>
        <li>复制内容</li>
        <li>代码块</li>
        <li>国际化</li>
        <li>外链</li>
        <li>Excel</li>
        <li>Zip</li>
        <li>
          <span>组件</span>
          <ul>
            <li>富文本编辑器</li>
            <li>Markdown支持</li>
            <li>SplitPane</li>
            <li>Sticky</li>
            <li>返回顶部</li>
            <li>拖拽看板</li>
            <li>图表</li>
            <li>svg图标</li>
            <li>表格</li>
            <li>tab</li>
          </ul>
          <span>小组件</span>
          <ul>
            <li>按钮效果</li>
            <li>链接效果</li>
            <li>其他效果</li>
          </ul>
        </li>
      </ul>
    </section>

    <router-view />
  </section>
</template>

<style lang="scss" scoped>
#active-container ul {
  list-style: inherit;
  padding: inherit;
  margin: inherit;
  padding-left: 20px;
  li {
    list-style: inherit;
  }
}
</style>
