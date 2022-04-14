/*
 * @Author: SLC
 * @Date: 2022-03-04 23:49:22
 * @LastEditors: SLC
 * @LastEditTime: 2022-03-12 18:22:58
 * @Description: 获取ip
 */

import {
  // ApiConfig,
  Api,
  Get,
  useContext,
  Middleware
} from '@midwayjs/hooks';
// import { Context } from '@midwayjs/koa';
import { logger } from './middleware/logger';
// import cors from '@koa/cors';
import { prisma } from './prisma';
import { Prisma } from '@prisma/client';

export const getIp = Api(
  Get(),
  Middleware([
    logger,
    // cors({ origin: '*' })
  ]),
  async () => {
    // const ip = useIp();
    // return {
    //   ip,
    // };

    // 查询
    // const posts =
    //   await prisma.post.findMany({
    //     // where: { published: true },
    //     include: { author: true },
    //   });
    // return posts;
    const posts =
      await prisma.user.findMany({
        // where: { published: true },
        include: { posts: true },
      });
    return posts;

    // 增加
    // const user: Prisma.UserCreateInput = {
    //   name: 'test123',
    //   email: 'test123@test.com',
    //   posts: {
    //     create: [
    //       {
    //         title: 'test123',
    //         content: 'test123',
    //         published: true,
    //       },
    //     ],
    //   },
    // }
    // const result =
    //   await prisma.user.create({
    //     data: user
    //   });
    // return result;
    // const result =
    //   await prisma.post.create({
    //     data: {
    //       title: 'test',
    //       content: 'test',
    //       published: true,
    //     },
    //   });
    // return result;
  }
);
