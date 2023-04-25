import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { Model } from 'mongoose';
import { CreateProductDto } from '../dto/create-product.dto';
import { UpdateProductDto } from '../dto/update-product.dto';
import { Product } from '../entities/product.entity';
import { ProductFactory } from '../factories/product.factory';
import { ProductDocument, ProductModel } from '../models/product.model';
import { ProductNotFoundError } from './errors/product-not-found.error';

@Injectable()
export class ProductsMongoDbRepository {
  constructor(
    @InjectModel(ProductModel.name)
    private productModel: Model<ProductDocument>,
  ) {}

  async findAll(): Promise<Product[]> {
    const products = await this.productModel.find().exec();

    return products.map((product) => {
      return ProductFactory.create(
        product.name,
        product.description,
        product.quantity,
        product.price,
        product._id,
        new Date(product.createdAt),
        new Date(product.updatedAt),
      );
    });
  }

  async findOneById(id: string): Promise<Product> {
    let product = await this.productModel
      .findOne({
        _id: id,
      })
      .exec();

    if (product === null) {
      throw new ProductNotFoundError();
    }

    return ProductFactory.create(
      product.name,
      product.description,
      product.quantity,
      product.price,
      product._id,
      product.createdAt,
      product.updatedAt,
    );
  }

  async create(body: CreateProductDto): Promise<Product> {
    let product = await this.productModel
      .findOne({
        name: body.name,
      })
      .exec();

    if (product !== null) {
      const { _id, name, description, quantity, price, createdAt } = product;

      product.quantity += body.quantity;

      await this.productModel.updateOne(
        { _id: product._id },
        {
          quantity: product.quantity,
          updatedAt: new Date(),
        },
      );

      return ProductFactory.create(
        name,
        description,
        quantity,
        price,
        _id,
        createdAt,
      );
    }

    const productEntity = ProductFactory.create(
      body.name,
      body.description,
      body.quantity,
      body.price,
    );

    this.productModel.insertMany({
      _id: productEntity.id.value,
      name: productEntity.name,
      description: productEntity.description,
      quantity: productEntity.quantity,
      price: productEntity.price,
      createdAt: productEntity.createdAt,
      updatedAt: productEntity.updatedAt,
    });

    return productEntity;
  }

  async updateById(id: string, body: UpdateProductDto): Promise<Product> {
    let product = await this.productModel
      .findOne({
        _id: id,
      })
      .exec();

    if (product === null) {
      throw new ProductNotFoundError();
    }

    const newData = {
      name: body.name ?? product.name,
      description: body.description ?? product.description,
      quantity: body.quantity ?? product.quantity,
      price: body.price ?? product.price,
      updatedAt: new Date(),
    };

    await this.productModel.updateOne({ _id: product._id }, newData);

    return ProductFactory.create(
      newData.name,
      newData.description,
      newData.quantity,
      newData.price,
      product._id,
      product.createdAt,
    );
  }

  async deleteById(id: string) {
    let product = await this.productModel
      .findOne({
        _id: id,
      })
      .exec();

    if (product === null) {
      throw new ProductNotFoundError();
    }

    await this.productModel.deleteOne({
      _id: product.id
    });
  }
}
