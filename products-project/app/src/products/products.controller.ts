import {
  Controller,
  Get,
  Post,
  Body,
  Patch,
  Param,
  Delete,
  ParseUUIDPipe,
  NotFoundException,
  InternalServerErrorException,
} from '@nestjs/common';
import { ProductsService } from './products.service';
import { CreateProductDto } from './dto/create-product.dto';
import { UpdateProductDto } from './dto/update-product.dto';
import { ProductListOutputDto } from './dto/output/product-list-output.dto';
import { ProductOutputDto } from './dto/output/product-output.dto';
import { ProductNotFoundError } from './repositories/errors/product-not-found.error';
import { ApiBody, ApiOperation, ApiResponse, ApiTags } from '@nestjs/swagger';
import { IndexProductSwagger } from './swagger/index-product.swagger';
import { CreateProductSwagger } from './swagger/create-product.swagger';
import { ShowProductSwagger } from './swagger/show-product.swagger';
import { UpdateProductSwagger } from './swagger/update-product.swagger';
import { UpdateProductBodySwagger } from './swagger/update-product-body.swagger';
import { BadRequestSwagger } from './swagger/response-failures/bad-request.swagger';
import { NotFoundSwagger } from './swagger/response-failures/not-found.swagger';

@Controller('api/v1/products')
@ApiTags('products')
export class ProductsController {
  constructor(private readonly productsService: ProductsService) {}
  
  @Get()
  @ApiOperation({ summary: 'List all products' })
  @ApiResponse({ 
    status: 200, 
    description: 'Products list',
    type: IndexProductSwagger,
    isArray: true
  })
  async index() {
    return ProductListOutputDto.transform(await this.productsService.findAll());
  }

  @Post()
  @ApiOperation({ summary: 'Create a product' })
  @ApiResponse({ 
    status: 201, 
    description: 'Product was created',
    type: CreateProductSwagger
  })
  @ApiResponse({ 
    status: 400, 
    description: 'Invalid parameters',
    type: BadRequestSwagger
  })
  async create(@Body() createProductDto: CreateProductDto) {
    return ProductOutputDto.transform(
      await this.productsService.create(createProductDto),
    );
  }

  @Get(':id')
  @ApiOperation({ summary: 'Show a product by id' })
  @ApiResponse({ 
    status: 201, 
    description: 'Show a product',
    type: ShowProductSwagger,
  })
  @ApiResponse({ 
    status: 400, 
    description: 'Invalid id',
    type: BadRequestSwagger
  })
  @ApiResponse({ 
    status: 404, 
    description: 'Product was not found',
    type: NotFoundSwagger
  })
  async show(@Param('id', new ParseUUIDPipe()) id: string) {
    try {
      return ProductOutputDto.transform(
        await this.productsService.findOneById(id),
      );
    } catch (error) {
      if (error instanceof ProductNotFoundError) {
        throw new NotFoundException('Product was not found');
      }

      throw new InternalServerErrorException();
    }
  }

  @Patch(':id')
  @ApiOperation({ summary: 'Update a product by id' })
  @ApiBody({ type: UpdateProductBodySwagger })
  @ApiResponse({ 
    status: 200, 
    description: 'Product was updated',
    type: UpdateProductSwagger
  })
  @ApiResponse({ 
    status: 400, 
    description: 'Invalid id',
    type: BadRequestSwagger
  })
  @ApiResponse({ 
    status: 404, 
    description: 'Product was not found',
    type: NotFoundSwagger
  })
  async update(
    @Param('id', new ParseUUIDPipe()) id: string,
    @Body() updateProductDto: UpdateProductDto,
  ) {
    try {
      return ProductOutputDto.transform(
        await this.productsService.updateById(id, updateProductDto),
      );
    } catch (error) {
      if (error instanceof ProductNotFoundError) {
        throw new NotFoundException('Product was not found');
      }

      throw new InternalServerErrorException();
    }
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Delete a product by id' })
  @ApiResponse({ status: 200, description: 'Product was deleted'})
  @ApiResponse({ 
    status: 400, 
    description: 'Invalid id',
    type: BadRequestSwagger
  })
  @ApiResponse({ 
    status: 404, 
    description: 'Product was not found',
    type: NotFoundSwagger
  })
  async destroy(@Param('id', new ParseUUIDPipe()) id: string) {
    try {
      return await this.productsService.deleteById(id);
    } catch (error) {
      if (error instanceof ProductNotFoundError) {
        throw new NotFoundException('Product was not found');
      }

      throw new InternalServerErrorException();
    }
  }
}
