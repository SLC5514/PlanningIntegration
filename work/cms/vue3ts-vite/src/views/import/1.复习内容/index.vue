<script lang="ts" setup></script>

<template>
  <section id="active-container">
    <h1>复习内容</h1>
    <section>
      <h2>HTML</h2>
      <h3>语义标签</h3>
      <div class="clear">
        <table
          class="left"
          border="1"
          cellspacing="0"
          cellpadding="0"
          style="border-collapse: collapse"
        >
          <thead>
            <tr>
              <th>标签</th>
              <th>语义</th>
              <th>标签</th>
              <th>语义</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td align="center" class="fB">header</td>
              <td>
                放在页面或布局的顶部，
                <br />一般放置导航栏或标题
              </td>
              <td align="center" class="fB">footer</td>
              <td>
                放在页面或者页面中某个区块的底部，
                <br />包含版权信息、联系方式等信息，
                <br />可以有多个
              </td>
            </tr>
            <tr>
              <td align="center" class="fB">section</td>
              <td>一组或者一节内容</td>
              <td align="center" class="fB">article</td>
              <td>
                用在相对比较独立、
                <br />完整的的内容区块
              </td>
            </tr>
            <tr>
              <td align="center" class="fB">nav</td>
              <td>
                表示页面的导航，
                <br />可以有多个
              </td>
              <td align="center" class="fB">aside</td>
              <td>
                一般使用在页面、文章的侧边栏、
                <br />广告、友情链接等区域
              </td>
            </tr>
            <tr>
              <td align="center" class="fB">figure</td>
              <td>
                独立的流内容
                <br />（图像、图表、照片、代码等等）
              </td>
              <td align="center" class="fB">figcaption</td>
              <td>定义 figure 元素的标题</td>
            </tr>
            <tr>
              <td align="center" class="fB">mark</td>
              <td>高亮</td>
              <td align="center" class="fB">time</td>
              <td>可以带格式的时间标签</td>
            </tr>
            <tr>
              <td align="center" class="fB">address</td>
              <td>作为联系信息出现</td>
              <td align="center" class="fB">hgroup</td>
              <td>
                如果有连续多个h1-h6标签
                <br />就用hgroup
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td>参考地址</td>
              <td colspan="3">
                <a
                  href="https://www.w3school.com.cn/html/html5_semantic_elements.asp"
                  target="_blank"
                >www.w3school.com.cn</a>、
                <a
                  href="https://www.w3cschool.cn/html5/html5-semantic-elements.html"
                  target="_blank"
                >www.w3cschool.cn</a>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </section>
    <section>
      <h2>CSS</h2>
      <h3>Sass</h3>
      <address>
        <span>参考地址：</span>
        <a href="https://www.sass.hk/docs/" target="_blank">www.sass.hk</a>
      </address>
    </section>
    <section>
      <h2>JS</h2>
      <h3>Ts</h3>
      <address>
        <span>参考地址：</span>
        <a href="https://www.typescriptlang.org/docs/" target="_blank">www.typescriptlang.org</a>
      </address>
    </section>

    <router-view />
  </section>
</template>

<style lang="scss" scoped></style>
