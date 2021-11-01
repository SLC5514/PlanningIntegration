<template>
  <section class="header pop-section">
    <div class="conleft fl">
      <ul class="left-list clearfix">
        <li class="logo">
          <img
            src="~assets/img/logo.png"
            alt="POP服装趋势"
          >
          <i class="drop-icon" />
          <div class="web-list">
            <ul>
              <li>
                <a
                  href="http://www.pop-fashion.com/"
                  target="_blank"
                  rel="nofollow"
                  title="服装"
                >
                  <em class="fashion" />服装
                </a>
              </li>
              <li>
                <a
                  href="http://www.pop-bags.com/"
                  target="_blank"
                  rel="nofollow"
                  title="箱包"
                >
                  <em class="bag" />箱包
                </a>
              </li>
              <li>
                <a
                  href="http://www.pop-shoe.com/"
                  target="_blank"
                  rel="nofollow"
                  title="鞋子"
                >
                  <em class="shoe" />鞋子
                </a>
              </li>
              <li>
                <a
                  href="http://www.51shoushi.com/"
                  target="_blank"
                  rel="nofollow"
                  title="首饰"
                >
                  <em class="dec" />首饰
                </a>
              </li>
              <li>
                <a
                  href="http://www.91jiafang.com/"
                  target="_blank"
                  rel="nofollow"
                  title="家纺"
                >
                  <em class="home" />家纺
                </a>
              </li>
              <li>
                <a
                  href="http://yuntu.pop136.com/"
                  target="_blank"
                  rel="nofollow"
                  title="云图"
                >
                  <em class="yuntu" />云图
                </a>
              </li>
              <li>
                <a
                  href="http://www.pop136.com/"
                  target="_blank"
                  rel="nofollow"
                  title="官网"
                >
                  <em class="guanw" />官网
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="vline" />
        <li class="lang">
          <a
            class="ch-lang"
            href="javascript:void(0)"
            title="中文"
          >
            <em />中文<i />
          </a>
          <div class="lang-list">
            <a
              class="korea-lang"
              href="http://k.pop-fashion.com"
              target="_blank"
            >
              <em />한국어
            </a>
            <a
              class="eng-lang"
              href="https://www.popfashioninfo.com/"
              target="_blank"
              title="English"
            >
              <em />English
            </a>
          </div>
        </li>
        <li class="yuntu">
          <a
            href="http://yuntu.pop136.com/"
            rel="nofollow"
            target="_blank"
            title="云图"
          >
            <em />云图
          </a>
        </li>
      </ul>
    </div>
    <div class="conright fr">
      <ul class="right-list clearfix">
        <li class="total_search">
          <div :class="{'search-box': true, 'cur': searchFocus}">
            <input
              class="search-btn"
              type="button"
              @click="() => searchFocus = true"
            >
            <input
              type="text"
              class="search-txt"
              :placeholder="searchFocus ? '时尚资讯一网打尽':'搜索'"
              growing-track="true"
              autocomplete="off"
              @focus="() => searchFocus = true"
              @blur="() => searchFocus = false"
            >
            <!-- <div class="search_listDown">
              <div class="menu">
                <ul
                  class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content"
                  id="ui-id-1"
                  tabindex="0"
                  style="display: none;"
                ></ul>
              </div>
              <div class="look-brand-box">
                <a
                  class="look-all-brand js-look-all-brand"
                  href="javascript:void(0)"
                  title="查看所有品牌"
                >查看所有品牌<em></em></a>
              </div>
            </div> -->
          </div>
        </li>
        <li class="tel-and-qq">
          <a
            class="tel-icon"
            href="javascript:void(0)"
          >
            <em />
          </a>
          <div class="tel-downlist">
            <p class="shouqian-tel">
              <em /><span>4008-210-500</span>（售前客服）
            </p>
            <p class="shouhou-tel">
              <em /><span>4008-210-662</span>（售后小秘书）
            </p>
            <a
              class="qq-contact"
              href="javascript:void(0);"
              title="QQ咨询"
            >
              <em />QQ咨询
            </a>
          </div>
        </li>
        <!-- <li class="find-brand">
          <a
            href="/brands/"
            title="发现品牌"
          ><em></em>发现品牌</a>
        </li> -->
        <!-- <li class="fg-line"></li> -->
        <!-- <li class="login_li">
          <a
            href="javascript:void(0);"
            class="loginLayer"
            rel="nofollow"
            title="登录"
          ><span class="lg"></span>登录</a>
        </li> -->
        <!-- <li class="fg-line white-line"></li> -->
        <!-- <li class="rgSearch">
          <a
            class="redRg"
            href="/member/register/"
            rel="nofollow"
            title="注册"
            target="_blank"
          ><span class="rg"></span>注册</a>
        </li> -->
        <!-- <li class="apply-item js-contact-qq-btn"><button title="咨询VIP服务">咨询VIP服务</button></li> -->
        <!-- <li class="all-nav-li js-all-nav-li">
          <span></span>
        </li> -->
      </ul>
    </div>
  </section>
</template>


<script>
export default {
  name: 'Header',
  data() {
    return {
      searchFocus: false
    }
  }
}
</script>

<style lang="less" scoped>
.header {
  height: 60px;
  line-height: 60px;
  color: #fff;
  font-size: 14px;
  .left-list > li,
  .right-list > li {
    height: 60px;
    line-height: 60px;
    padding: 0 5px;
    margin-right: 10px;
    float: left;
  }
  .conleft {
    margin-left: 30px;
    .left-list {
      & > li {
        a {
          color: #fff;
          display: inline-block;
          line-height: 60px;
          cursor: pointer;
          font-size: 12px;
        }
        &:hover {
          &.logo {
            .web-list {
              display: block;
            }
          }
          &.lang {
            a {
              color: #d8b056;
            }
            .lang-list {
              display: block;
            }
          }
          &.yuntu {
            a {
              color: #d8b056;
              em {
                background: url(~assets/img/yuntu-hover.png);
                background-size: 100% 100%;
              }
            }
          }
        }
        &.logo {
          position: relative;
          img {
            display: inline-block;
            vertical-align: middle;
          }
          .drop-icon {
            display: inline-block;
            width: 0px;
            height: 0px;
            margin-left: 10px;
            border: 4px solid transparent;
            border-top-color: #fff;
            vertical-align: middle;
            position: relative;
            top: 2px;
          }
          .web-list {
            width: 100%;
            position: absolute;
            left: 0;
            top: 60px;
            background: #13181e;
            display: none;
            ul {
              padding: 10px 0;
              li {
                a {
                  height: 30px;
                  padding-left: 10px;
                  line-height: 30px;
                  font-size: 12px;
                  color: #fff;
                  display: block;
                  em {
                    background: url(~assets/img/icons.png) no-repeat;
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    vertical-align: middle;
                    margin-right: 5px;
                    &.fashion {
                      background-position: -58px -417px;
                      width: 13px;
                      height: 14px;
                    }
                    &.bag {
                      background-position: 0 -205px;
                    }
                    &.shoe {
                      background-position: -17px -205px;
                      height: 8px;
                    }
                    &.dec {
                      background-position: -35px -205px;
                      height: 13px;
                    }
                    &.home {
                      background-position: -54px -205px;
                      width: 13px;
                    }
                    &.yuntu {
                      background: url(~assets/img/yuntu.png);
                      background-size: 100% 100%;
                    }
                    &.guanw {
                      background-position: -72px -205px;
                      width: 13px;
                      height: 13px;
                    }
                  }
                }
                &:hover {
                  a {
                    color: #d8b056;
                    em {
                      &.fashion {
                        background-position: -79px -417px;
                      }
                      &.bag {
                        background-position: 0 -221px;
                      }
                      &.shoe {
                        background-position: -17px -221px;
                      }
                      &.dec {
                        background-position: -35px -221px;
                      }
                      &.home {
                        background-position: -54px -221px;
                      }
                      &.yuntu {
                        background: url(~assets/img/yuntu-hover.png);
                        background-size: 100% 100%;
                      }
                      &.guanw {
                        background-position: -72px -221px;
                      }
                    }
                  }
                }
              }
            }
          }
        }
        &.vline {
          width: 1px;
          height: 14px;
          background: #61656b;
          padding: 0;
          margin-top: 24px;
        }
        &.lang {
          position: relative;
          em {
            background: url(~assets/img/vi_icon.png) no-repeat 0 0;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
            margin-top: -2px;
          }
          i {
            background: url(~assets/img/vi_icon.png) no-repeat;
            width: 6px;
            height: 4px;
            display: inline-block;
            margin-left: 10px;
            vertical-align: middle;
          }
          .ch-lang em {
            background-position: -11px 0;
            width: 18px;
            height: 12px;
          }
          .lang-list {
            position: absolute;
            left: 0;
            top: 60px;
            background: #13181e;
            width: 100%;
            display: none;
            a {
              display: block;
              height: 35px;
              line-height: 35px;
              color: #fff;
              cursor: pointer;
              padding-left: 10px;
              &:hover {
                color: #d8b056;
              }
              &.korea-lang em {
                background-position: -36px 0;
                width: 16px;
                height: 16px;
              }
              &.eng-lang em {
                background-position: -59px 0;
                width: 14px;
                height: 11px;
              }
            }
          }
        }
        &.yuntu {
          a {
            em {
              display: inline-block;
              vertical-align: middle;
              margin-right: 10px;
              margin-top: -2px;
              width: 12px;
              height: 12px;
              background: url(~assets/img/yuntu.png);
              background-size: 100% 100%;
            }
          }
        }
      }
    }
  }
  .conright {
    .right-list {
      padding-right: 60px;
      position: relative;
      & > li {
        position: relative;
        &.total_search {
          width: 80px;
          .search-box {
            position: absolute;
            right: 10px;
            height: 60px;
            width: 80px;
            background: transparent;
            transition: all 0.5s ease-out;
            .search-btn {
              background: url(~assets/img/vi_icon.png) no-repeat 10px -22px;
              width: 27px;
              height: 16px;
              position: absolute;
              right: 0;
              top: 21px;
              cursor: pointer;
              border: none;
            }
            .search-txt {
              width: 100%;
              height: 20px;
              border: 0 none;
              border-bottom: 1px solid #878a8d;
              background: none;
              color: #fff;
              font-size: 14px;
              margin-top: 19px;
              padding-right: 25px;
              &:-moz-placeholder {
                /* Mozilla Firefox 4 to 18 */
                color: #fff;
                opacity: 1;
              }
              &::-moz-placeholder {
                /* Mozilla Firefox 19+ */
                color: #fff;
                opacity: 1;
              }
              &:-ms-input-placeholder {
                color: #fff;
                opacity: 1;
              }
              &::-webkit-input-placeholder {
                color: #fff;
                opacity: 1;
              }
            }
            &.cur {
              width: 300px;
              .search-txt {
                &:-moz-placeholder {
                  /* Mozilla Firefox 4 to 18 */
                  color: #999;
                  opacity: 1;
                }
                &::-moz-placeholder {
                  /* Mozilla Firefox 19+ */
                  color: #999;
                  opacity: 1;
                }
                &:-ms-input-placeholder {
                  color: #999;
                  opacity: 1;
                }
                &::-webkit-input-placeholder {
                  color: #999;
                  opacity: 1;
                }
              }
            }
          }
        }
        &.tel-and-qq {
          a em {
            display: inline-block;
            vertical-align: middle;
            width: 16px;
            height: 18px;
          }
          a.tel-icon em {
            background: url(~assets/img/vi_icon.png) no-repeat 0 0;
            background-position: -124px 0;
          }
          &:hover {
            a.tel-icon em {
              background-position: -39px -45px;
            }
          }
        }
      }
    }
  }
}
</style>
