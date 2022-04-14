/*
 * @Author: SLC
 * @Date: 2022-03-12 18:19:28
 * @LastEditors: SLC
 * @LastEditTime: 2022-03-12 18:31:14
 * @Description: file content
 */

import {
  // ApiConfig,
  Api,
  Get,
  // useContext,
  Middleware
} from '@midwayjs/hooks';
// import { Context } from '@midwayjs/koa';
import { logger } from './middleware/logger';
// import cors from '@koa/cors';
// import { prisma } from './prisma';
// import { Prisma } from '@prisma/client';

export const addUser = Api(
  Get(),
  Middleware([
    logger,
    // cors({ origin: '*' })
  ]),
  async () => {
    // const result =
    //   await prisma.post.create({
    //     data: {
    //       title: 'test',
    //       content: 'test',
    //       published: true,
    //     },
    //   });
    // return result;
    return []
  }
);

// export const delUser = Api(
//   Get(),
//   Middleware([
//     logger
//   ]),
//   async () => {
//     return []
//   }
// );

// export const editUser = Api(
//   Get(),
//   Middleware([
//     logger
//   ]),
//   async () => {
//     return []
//   }
// );

// export const findUser = Api(
//   Get(),
//   Middleware([
//     logger
//   ]),
//   async () => {
//     // const posts =
//     //   await prisma.post.findMany({
//     //     // where: { published: true },
//     //     include: { author: true },
//     //   });
//     // return posts;
//     const posts =
//       await prisma.user.findMany({
//         // where: { published: true },
//         include: { posts: true },
//       });
//     return posts;
//   }
// );
