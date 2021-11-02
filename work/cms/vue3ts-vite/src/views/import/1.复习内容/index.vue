<script lang="ts" setup></script>

<template>
  <section id="active-container">
    <h1>复习内容</h1>

    <section>
      <h5>外链</h5>
      <address>
        <span>参考地址：</span>
        <a href="https://www.w3.org/" target="_blank">万维网联盟（W3C）</a>、
        <a href="https://chinese.freecodecamp.org/" target="_blank">免费学习编程（fcc）</a>、
        <a href="https://ebookfoundation.github.io/free-programming-books/books/free-programming-books-zh.html" target="_blank">免费编程书籍</a>、
        <a href="https://github.com/justjavac/free-programming-books-zh_CN" target="_blank">免费的编程中文书籍索引</a>、
        <br>
        <a href="https://www.w3cschool.cn/" target="_blank">w3cschool - 编程狮，随时随地学编程</a>、
        <a href="https://wangdoc.com/" target="_blank">网道 - 互联网开发文档</a>、
        <a href="https://www.30secondsofcode.org/" target="_blank">30 seconds of code</a>、
        <a href="https://css-tricks.com/" target="_blank">CSS-Tricks</a>、
        <a href="https://github.com/stephentian/33-js-concepts" target="_blank">JavaScript开发者应懂的33个概念</a>
      </address>
    </section>

    <section>
      <h2 v-toggle>HTML</h2>

      <section>
        <h3 v-toggle>语义标签</h3>
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
    </section>

    <section>
      <h2 v-toggle>CSS</h2>

      <section>
        <h3>Sass</h3>
        <address>
          <span>参考地址：</span>
          <a href="https://www.sass.hk/docs/" target="_blank">www.sass.hk</a>
        </address>
      </section>

      <section>
        <h3>Less</h3>
        <address>
          <span>参考地址：</span>
          <a href="http://lesscss.cn/" target="_blank">lesscss.cn</a>
        </address>
      </section>
    </section>

    <section>
      <h2 v-toggle>JS</h2>

      <section>
        <h3 v-toggle>设计模式</h3>

        <section>
          <h4>构造函数模式</h4>
          <pre>
            <code class="hljs">/**
    * 构造一个动物的函数
    */
    function Animal(name, color){
        this.name = name;
        this.color = color;
        this.getName = function(){
            return this.name;
        }
    }
    // 实例一个对象
    var cat = new Animal('猫', '白色');
    console.log( cat.getName() );</code>
          </pre>
        </section>

        <section>
          <h4>工厂模式</h4>
          <pre>
            <code class="hljs">/**
    * 工厂模式
    */
    function Animal(opts){
        var obj = new Object();
        obj.name = opts.name;
        obj.color = opts.color;
        obj.getInfo = function(){
            return '名称：'+obj.name +'， 颜色：'+ obj.color;
        }
        return obj;
    }
    var cat = Animal({name: '波斯猫', color: '白色'});
    cat.getInfo();</code>
          </pre>
        </section>

        <section>
          <h4>模块模式</h4>
          <pre>
            <code class="hljs">/**
    * 模块模式 = 封装大部分代码，只暴露必需接口
    */
    var Car = (function(){
        var name = '法拉利';
        function sayName(){
            console.log( name );
        }
        function getColor(name){
            console.log( name );
        }
        return {
            name: sayName,
            color: getColor
        }
    })();
    Car.name();
    Car.color('红色');</code>
          </pre>
        </section>

        <section>
          <h4>混合模式</h4>
          <pre>
            <code class="hljs">/**
    * 混合模式 = 原型模式 + 构造函数模式
    */
    function Animal(name, color){
        this.name = name;
        this.color = color;

        console.log( this.name  +  this.color)
    }
    Animal.prototype.getInfo = function(){
        console.log('名称：'+ this.name);
    }

    function largeCat(name, color){
        Animal.call(null, name, color);

        this.color = color;
    }

    largeCat.prototype = create(Animal.prototype);
    function create (parentObj){
        function F(){}
        F.prototype = parentObj;
        return new F();
    };

    largeCat.prototype.getColor = function(){
        return this.color;
    }
    var cat = new largeCat("Persian", "白色");
    console.log( cat )</code>
          </pre>
        </section>

        <section>
          <h4>单例模式</h4>
          <pre>
            <code class="hljs">/**
    * 在执行当前 Single 只获得唯一一个对象
    */
    var Single = (function(){
        var instance;
        function init() {
            //define private methods and properties
            //do something
            return {
                //define public methods and properties
            };
        }

        return {
            // 获取实例
            getInstance:function(){
                if(!instance){
                    instance = init();
                }
                return instance;
            }
        }
    })();

    var obj1 = Single.getInstance();
    var obj2 = Single.getInstance();

    console.log(obj1 === obj2);</code>
          </pre>
        </section>

        <section>
          <h4>发布订阅模式</h4>
          <pre>
            <code class="hljs">/**
    * 发布订阅模式
    */
    var EventCenter = (function(){
        var events = {};
        /*
        {
          my_event: [{handler: function(data){xxx}}, {handler: function(data){yyy}}]
        }
        */
        // 绑定事件 添加回调
        function on(evt, handler){
            events[evt] = events[evt] || [];
            events[evt].push({
                handler:handler
            })
        }
        function fire(evt, arg){
            if(!events[evt]){
                return
            }
            for(var i=0; i &lt; events[evt].length; i++){
                events[evt][i].handler(arg);
            }
        }
        function off(evt){
            delete events[evt];
        }
        return {
            on:on,
            fire:fire,
            off:off
        }
    }());

    var number = 1;
    EventCenter.on('click', function(data){
        console.log('click 事件' + data + number++ +'次');
    });
    EventCenter.off('click');   //  只绑定一次
    EventCenter.on('click', function(data){
        console.log('click 事件' + data + number++ +'次');
    });

    EventCenter.fire('click', '绑定');</code>
          </pre>
        </section>
      </section>

      <section>
        <h3>Ts</h3>
        <address>
          <span>参考地址：</span>
          <a href="https://www.typescriptlang.org/docs/" target="_blank">www.typescriptlang.org</a>
        </address>
      </section>

      <section>
        <h3>NodeJs</h3>
        <address>
          <span>参考地址：</span>
          <a href="http://nodejs.cn/learn" target="_blank">nodejs.cn</a>
        </address>
      </section>

      <section>
        <h3>算法</h3>
        <address>
          <span>参考地址：</span>
          <a href="https://github.com/trekhleb/javascript-algorithms/blob/master/README.zh-CN.md" target="_blank">JavaScript 算法与数据结构</a>
        </address>
      </section>
    </section>

    <section>
      <h2 v-toggle>框架</h2>

      <section>
        <h3>Vue</h3>
        <h3>React</h3>
        <h3>Electron</h3>
        <h3>Egg</h3>
        <h3>Koa</h3>
      </section>
    </section>

    <section>
      <h2 v-toggle>打包工具</h2>

      <section>
        <h3>Vite</h3>
        <h3>Vue-cli</h3>
        <h3>Webpack</h3>
        <h3>Gulp</h3>
      </section>
    </section>

    <section>
      <h2>杂项</h2>
    </section>

    <router-view />
  </section>
</template>

<style lang="scss" scoped></style>
