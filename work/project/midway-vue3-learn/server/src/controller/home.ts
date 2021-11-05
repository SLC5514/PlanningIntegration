import { Controller, Get, Inject, Provide } from '@midwayjs/decorator';
import { Context } from '@midwayjs/web';
import { InjectEntityModel } from '@midwayjs/orm';
import { Photo } from '../entity/photo';
import { Repository } from 'typeorm';

@Provide()
@Controller('/')
export class HomeController {
  @Inject()
  ctx: Context;

  @InjectEntityModel(Photo)
  photoModel: Repository<Photo>;

  @Get('/')
  async home() {
    let allPhotos = await this.photoModel.find();
    console.log('All photos from the db: ', allPhotos);

    return 'Hello Midwayjs!';
    // await this.ctx.render('hello.ejs', {
    //   data: 'world',
    // });
  }
}
