import { Module } from '@nestjs/common';
import { ProductsService } from './products.service';
import { ProductsController } from './products.controller';
import { ProductsRepository } from './repositories/products.repository';
import { ProductsMongoDbRepository } from './repositories/products.mongodb.repository';
import { ProductModel, ProductSchema } from './models/product.model';
import { ProductsInMemoryRepository } from './repositories/products.in-memory.repository';
import { MongooseModule } from '@nestjs/mongoose';

@Module({
  imports: [
    MongooseModule.forFeature([{ name: ProductModel.name, schema: ProductSchema }])
  ],
  controllers: [ProductsController],
  providers: [
    ProductsService,
    ProductsMongoDbRepository,
    ProductsInMemoryRepository,
    {
      provide: ProductsRepository.name,
      useExisting: ProductsMongoDbRepository
    }
  ]
})
export class ProductsModule {}
