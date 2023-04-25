import { Product } from '../entities/product.entity';

export class ProductFactory {
  static create(
    name: string,
    description: string,
    quantity: number,
    price: number,
    id?: any,
    createdAt?: Date,
    updatedAt?: Date,
  ) {
    return new Product(
      name,
      description,
      quantity,
      price,
      id,
      createdAt,
      updatedAt,
    );
  }
}
