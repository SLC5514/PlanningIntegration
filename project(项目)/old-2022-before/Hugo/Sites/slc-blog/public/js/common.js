/*
 * @Author: SLC
 * @Date: 2021-09-15 13:10:10
 * @LastEditors: SLC
 * @LastEditTime: 2021-09-16 09:37:57
 * @Description: file content
 */

const header = $('header');

// 头部缩小化
$(window).on('scroll', function() {
  if (this.scrollY > 100) {
    header.addClass('min');
  } else {
    header.removeClass('min');
  }
})
