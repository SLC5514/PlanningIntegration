import { App, Configuration } from '@midwayjs/decorator';
import { ILifeCycle } from '@midwayjs/core';
import * as view from '@midwayjs/view-ejs';
import * as orm from '@midwayjs/orm';
import { Application } from 'egg';
import { join } from 'path';

@Configuration({
  imports: [
    view,
    orm,
  ],
  importConfigs: [join(__dirname, './config')],
  conflictCheck: true,
})
export class ContainerLifeCycle implements ILifeCycle {
  @App()
  app: Application;

  async onReady() {}
}
