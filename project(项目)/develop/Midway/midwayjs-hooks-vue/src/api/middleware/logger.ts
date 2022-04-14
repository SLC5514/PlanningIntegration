/*
 * @Author: SLC
 * @Date: 2022-03-05 23:09:07
 * @LastEditors: SLC
 * @LastEditTime: 2022-03-05 23:16:35
 * @Description: file content
 */

import { useContext } from '@midwayjs/hooks';
import { Context } from '@midwayjs/koa';

export const logger = async (next: any) => {
  const ctx = useContext<Context>();

  console.log(
    `<-- [${ctx.method}] ${ctx.url}`
  );

  const start = Date.now();
  await next();
  const cost = Date.now() - start;

  console.log(
    `--> [${ctx.method}] ${ctx.url} ${cost}ms`
  );
};
