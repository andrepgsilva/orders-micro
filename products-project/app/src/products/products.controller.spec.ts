import { Test, TestingModule } from '@nestjs/testing';
import { CreateProductDto } from './dto/create-product.dto';
import { UpdateProductDto } from './dto/update-product.dto';
import { Product } from './entities/product.entity';
import { ProductFactory } from './factories/product.factory';
import { TransformDatetimeToAmericanFormat } from './helpers/transform-datetime-to-american-format';
import { ProductsController } from './products.controller';
import { ProductsService } from './products.service';

const productList: Product[] = [
  ProductFactory.create('great product', 'nice description', 21, 43121),
  ProductFactory.create('great product 2', 'nice description 3', 55, 92153),
  ProductFactory.create('great product 3', 'nice description 4', 43, 83109),
]

const exampleProduct = ProductFactory.create('great product', 'nice description', 21, 43121);
const exampleProductForUpdate = ProductFactory.create('great product for update', 'nice description', 52, 23321);

const firstProductUuid = productList[0].id.value;

describe('ProductsController', () => {
  let productsController: ProductsController;
  let productsService: ProductsService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [ProductsController],
      providers: [
        {
          provide: ProductsService,
          useValue: {
            findAll: jest.fn().mockResolvedValue(productList),
            findOneById: jest.fn().mockResolvedValue(productList[0]),
            create: jest.fn().mockResolvedValue(exampleProduct),
            updateById: jest.fn().mockResolvedValue(exampleProductForUpdate),
            deleteById: jest.fn().mockResolvedValue(undefined),
          }
        }
      ],
    }).compile();

    productsController = module.get<ProductsController>(ProductsController);
    productsService = module.get<ProductsService>(ProductsService);
  });

  it('should be defined', () => {
    expect(productsController).toBeDefined();
    expect(productsService).toBeDefined();
  });

  describe('index', () => {
    it('should return a product list', async () => {
      const result = await productsController.index();

      expect(result.length).toBe(productList.length);
      expect(typeof result).toEqual('object');
      expect(productsService.findAll).toHaveBeenCalledTimes(1);
    });

    it('should throw an exception', async () => {
      jest.spyOn(productsService, 'findAll').mockRejectedValue(new Error());

      expect(productsController.index()).rejects.toThrow();
    });
  });

  describe('show', () => {
    it('should get product successfully', async () => {
      const result = await productsController.show(firstProductUuid);
      
      const { id, name, description, quantity, price, createdAt, updatedAt } = productList[0];

      expect(result).toStrictEqual({ 
        id: id.value, 
        name, 
        description, 
        quantity, 
        price,
        created_at: TransformDatetimeToAmericanFormat.execute(createdAt), 
        updated_at: TransformDatetimeToAmericanFormat.execute(updatedAt), 
      });

      expect(productsService.findOneById).toBeCalledTimes(1);
    });

    it('should throw an exception', async () => {
      jest.spyOn(productsService, 'findOneById').mockRejectedValue(new Error());

      const result = productsController.show(firstProductUuid);
      
      expect(result).rejects.toThrow();
    });
  });

  describe('create', () => {
    it('should create a product', async () => {
      const body = {
        name: 'great product',
        description: 'nice description',
        quantity: 21,
        price: 43121
      } as CreateProductDto;

      const { name, description, quantity, price } = await productsController.create(body);

      expect(body).toStrictEqual({ name, description, quantity, price });
      expect(productsService.create).toBeCalledTimes(1);
    });

    it('should throw an exception', async () => {
      const body = {
        name: 'great product',
        description: 'nice description',
        quantity: 21,
        price: 43121
      } as CreateProductDto;

      jest.spyOn(productsService, 'create').mockRejectedValue(new Error());

      expect(productsController.create(body)).rejects.toThrow();
      expect(productsService.create).toBeCalledTimes(1);
    });
  });

  describe('update', () => {
    it('should update a product', async () => {
      const body: UpdateProductDto = {
        name: 'great product for update',
        description: 'nice description'
      };

      const result = await productsController.update(firstProductUuid, body);
      
      expect(result.name).toEqual(body.name);
      expect(productsService.updateById).toBeCalledTimes(1);
    });

    it('should throw an exception', async () => {
      jest.spyOn(productsService, 'updateById').mockRejectedValue(new Error());

      const body: UpdateProductDto = {
        name: 'great product for update',
        description: 'nice description'
      };

      expect(productsController.update(firstProductUuid, body)).rejects.toThrow();
      expect(productsService.updateById).toHaveBeenCalledTimes(1);
    });
  });

  describe('destroy', () => {
    it('should destroy a product', async() => {
      expect(await productsController.destroy(firstProductUuid)).toBe(undefined);
      expect(productsService.deleteById).toHaveBeenCalledTimes(1);
    });

    it('it should throw an expection', async() => {
      jest.spyOn(productsService, 'deleteById').mockRejectedValue(new Error());

      expect(productsController.destroy(firstProductUuid)).rejects.toThrow();
      expect(productsService.deleteById).toHaveBeenCalledTimes(1);
    });
  })
});