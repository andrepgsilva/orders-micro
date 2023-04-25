import { CreateProductDto } from "../dto/create-product.dto";
import { UpdateProductDto } from "../dto/update-product.dto";
import { Product } from "../entities/product.entity";

export abstract class ProductsRepository {
  abstract findAll(): Promise<Product[]>;
  abstract findOneById(id: string): Promise<Product>;
  abstract create(body: CreateProductDto): Promise<Product>;
  abstract updateById(id: string, body: UpdateProductDto): Promise<Product>;
  abstract deleteById(id: string): any;
}