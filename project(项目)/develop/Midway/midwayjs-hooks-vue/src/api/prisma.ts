/*
 * @Author: SLC
 * @Date: 2022-03-06 01:09:14
 * @LastEditors: SLC
 * @LastEditTime: 2022-03-06 15:55:29
 * @Description: file content
 */

import { PrismaClient } from '@prisma/client';

export const prisma = new PrismaClient({
  log: ['query', 'info', 'warn', 'error'],
  errorFormat: 'pretty',
});

prisma.$on('beforeExit', async () => {
  console.log('beforeExit hook')
})
