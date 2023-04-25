import { Injectable } from '@nestjs/common';
import { CreateProductDto } from '../dto/create-product.dto';
import { UpdateProductDto } from '../dto/update-product.dto';
import { Product } from '../entities/product.entity';
import { ProductFactory } from '../factories/product.factory';
import { TransformDatetimeToAmericanFormat } from '../helpers/transform-datetime-to-american-format';
import { ProductNotFoundError } from './errors/product-not-found.error';
import { ProductsRepository } from './products.repository';

type InMemoryProduct = {
  id: string;
  name: string;
  description: string;
  quantity: number;
  price: number;
  created_at: string;
  updated_at: string;
};

const currentDatetimeAmericanFormat = TransformDatetimeToAmericanFormat.execute();

@Injectable()
export class ProductsInMemoryRepository extends ProductsRepository {
  products: InMemoryProduct[] = [
    {
      id: '3435a022-d268-444d-8510-fa379d8a95e1',
      name: 'great product',
      description: 'product description',
      quantity: 20,
      price: 29312,
      created_at: currentDatetimeAmericanFormat,
      updated_at: currentDatetimeAmericanFormat,
    },

    {
      id: '3435a022-d268-444d-8510-fa379d8a95e4',
      name: 'another product',
      description: 'amazing description',
      quantity: 33,
      price: 31457,
      created_at: currentDatetimeAmericanFormat,
      updated_at: currentDatetimeAmericanFormat,
    },
  ];

  async findAll(): Promise<Product[]> {
    return this.products.map((product) => {
      return ProductFactory.create(
        product.name,
        product.description,
        product.quantity,
        product.price,
        product.id,
        new Date(product.created_at),
        new Date(product.updated_at),
      );
    });
  }

  async findOneById(id: string): Promise<Product> {
    const products = this.products.filter((product) => {
      return id === product.id;
    });

    if (products.length === 0) {
      throw new ProductNotFoundError();
    }

    const result = products[0];
    return ProductFactory.create(
      result.name,
      result.description,
      result.quantity,
      result.price,
      result.id,
      new Date(result.created_at),
      new Date(result.updated_at),
    );
  }

  async create(body: CreateProductDto): Promise<Product> {
    let productFoundIndex = -1;

    this.products.forEach((product, index) => {
      if (product.name === body.name) {
        productFoundIndex = index;
        return;
      }
    });

    let product: Product;

    if (productFoundIndex !== -1) {
      const { 
        id, name, 
        description, quantity, 
        price, created_at 
      } = this.products[productFoundIndex];
      product = ProductFactory.create(name, description, quantity, price, id, new Date(created_at));

      product.quantity += body.quantity;

      this.products[productFoundIndex] = {
        id,
        name,
        description,
        quantity,
        price,
        created_at,
        updated_at: currentDatetimeAmericanFormat
      };

      return product;
    }

    product = ProductFactory.create(
      body.name,
      body.description,
      body.quantity,
      body.price,
    );

    this.products.push({
      id: product.id.value,
      name: body.name,
      description: body.description,
      quantity: body.quantity,
      price: body.price,
      created_at: currentDatetimeAmericanFormat,
      updated_at: currentDatetimeAmericanFormat
    });

    return product;
  }

  async updateById(id: string, body: UpdateProductDto): Promise<Product> {
    let productFoundIndex = -1;

    this.products.forEach((product, index) => {
      if (product.id === id) {
        productFoundIndex = index;
        return;
      }
    });

    if (productFoundIndex === -1) {
      throw new ProductNotFoundError();
    }

    const product = this.products[productFoundIndex];
    product.name = body.name ?? product.name;
    product.description = body.description ?? product.description;
    product.quantity = body.quantity ?? product.quantity;
    product.price = body.price ?? product.price;
    product.updated_at = currentDatetimeAmericanFormat;

    this.products[productFoundIndex] = product;

    return ProductFactory.create(
      product.name,
      product.description,
      product.quantity,
      product.price,
      new Date(product.created_at)
    );
  }

  deleteById(id: string) {
    let productFoundIndex = -1;

    this.products.forEach((product, index) => {
      if (product.id === id) {
        productFoundIndex = index;
        return;
      }
    });

    if (productFoundIndex === -1) {
      throw new ProductNotFoundError();
    }

    this.products.splice(productFoundIndex, 1);
  }
}
