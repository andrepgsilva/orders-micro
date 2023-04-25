import { Inject, Injectable } from '@nestjs/common';
import { CreateProductDto } from './dto/create-product.dto';
import { UpdateProductDto } from './dto/update-product.dto';
import { ProductsRepository } from './repositories/products.repository';

@Injectable()
export class ProductsService {
  constructor(
    @Inject('ProductsRepository')
    private readonly productsRepository: ProductsRepository
  ) {}

  async findAll() {
    return await this.productsRepository.findAll();
  }

  async create(createProductDto: CreateProductDto) {
    return await this.productsRepository.create(createProductDto);
  }

  async findOneById(id: string) {
    return await this.productsRepository.findOneById(id);
  }

  async updateById(id: string, updateProductDto: UpdateProductDto) {
    return await this.productsRepository.updateById(id, updateProductDto);
  }

  async deleteById(id: string) {
    return await this.productsRepository.deleteById(id);
  }
}
