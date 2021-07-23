/*
 * @Author: SLC
 * @Date: 2021-07-23 15:18:42
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-23 16:01:54
 * @Description: file content
 */

import { Provide } from '@midwayjs/decorator'
import { IWebMiddleware, IMidwayWebNext } from '@midwayjs/web'
import { Context } from 'egg'

@Provide()
export class NotFoundMiddleware implements IWebMiddleware {
  resolve () {
    return async (ctx: Context, next: IMidwayWebNext) => {
      await next()
      if (ctx.status === 404) {
        // 手动建立 /web/pages/404 相关文件
        ctx.redirect('/404')
      }
    }
  }
}
