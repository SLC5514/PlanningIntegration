/*
 * @Author: SLC
 * @Date: 2021-09-15 13:10:10
 * @LastEditors: SLC
 * @LastEditTime: 2021-09-17 14:20:35
 * @Description: file content
 */

const header = $('header');

$('.js-banner').append(`<img class="banner-picture" src="https://picsum.photos/${document.documentElement.clientWidth}/300?blur" onload="this.style.height = '300px';">`);

// 头部缩小化
$(window).on('scroll', function() {
  if (this.scrollY > 100) {
    header.addClass('min');
  } else {
    header.removeClass('min');
  }
})
