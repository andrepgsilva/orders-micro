import { Test, TestingModule } from '@nestjs/testing';
import { Product } from './entities/product.entity';
import { TransformDatetimeToAmericanFormat } from './helpers/transform-datetime-to-american-format';
import { ProductsService } from './products.service';
import { ProductsInMemoryRepository } from './repositories/products.in-memory.repository';
import { ProductsRepository } from './repositories/products.repository';

let products: any;
const currentDatetimeAmericanFormat = TransformDatetimeToAmericanFormat.execute();

beforeEach(() => {
  products = [
    {
      id: '3435a022-d268-444d-8510-fa379d8a95e1',
      name: 'great product',
      description: 'product description',
      quantity: 20,
      price: 29312,
      created_at: currentDatetimeAmericanFormat,
      updated_at: currentDatetimeAmericanFormat
    }, 
    {
      id: '3435a022-d268-444d-8510-fa379d8a95e4',
      name: 'another product',
      description: 'amazing description',
      quantity: 33,
      price: 31457,
      created_at: currentDatetimeAmericanFormat,
      updated_at: currentDatetimeAmericanFormat
    },
  ];
});

describe('ProductsService', () => {
  let productsService: ProductsService;
  let productsRepository: ProductsInMemoryRepository;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [
        ProductsService,
        ProductsInMemoryRepository,
        {
          provide: ProductsRepository.name,
          useExisting: ProductsInMemoryRepository
        }
      ],
    }).compile();

    productsService = module.get<ProductsService>(ProductsService);
    productsRepository = module.get<ProductsInMemoryRepository>(ProductsInMemoryRepository);
    productsRepository.products = products;
  });

  it('should be defined', () => {
    expect(productsService).toBeDefined();
    expect(productsRepository).toBeDefined();
  });

  describe('Find a product', () => {
    it('it can find a product by id', async() => {
      let id = products[0].id;
      expect(productsRepository.findOneById(id)).resolves.toBeInstanceOf(Product);
    })

    it('it cannot find a product by id', async() => {
      let id = products[0].id + '021';
      expect(productsRepository.findOneById(id)).rejects.toThrow();
    })
  });

  describe('Find all products', () => {
    it('should return all products', async () => {
      expect(await productsService.findAll()).toBeInstanceOf(Array);
      expect((await productsService.findAll())[0]).toBeInstanceOf(Product);
    });

    it('should thrown an exception', async () => {
      jest.spyOn(productsRepository, 'findAll').mockRejectedValueOnce(new Error());
      
      expect(productsService.findAll()).rejects.toThrow();
    });
  });

  describe('Create a product', () => {
    it('can create a product', async () => {
      const data = {
        name: 'GOAT Product',
        description: 'Goat Product Description',
        quantity: 20,
        price: 20000
      };

      const product = await productsService.create(data);
      expect(data).toStrictEqual({
        name: product.name,
        description: product.description,
        quantity: product.quantity,
        price: product.price
      });
    });

    it('updates product quantity when it already exists', async () => {
      const data = {
        name: 'GOAT Product',
        description: 'Goat Product Description',
        quantity: 20,
        price: 20000
      };

      await productsService.create(data);

      data.quantity = 10;
      const product = await productsService.create(data);

      expect(product.quantity).toBe(30);
    });

    it('it cannot create a product with a wrong quantity or price', async () => {
      const data = {
        name: 'GOAT Product 2',
        description: 'Goat Product Description',
        quantity: -1,
        price: 20000
      };

      expect(productsService.create(data)).rejects.toThrow();
    });
  })

  describe('Update a product', () => {
    it('it can update a product', async() => {
      let id = products[0].id;
      const updatedData = {
        name: 'Updated Project',
        description: 'Updated Description',
        quantity: 99,
        price: 23145
      };

      const result = await productsRepository.updateById(id, updatedData);

      expect(result).toBeInstanceOf(Product);
      expect(updatedData).toStrictEqual({
        name: result.name,
        description: result.description,
        quantity: result.quantity,
        price: result.price
      });
    });

    it('it cannot update a product that does not exist', async() => {
      let id = products[0].id + 'a';
      const updatedData = {
        name: 'Updated Project',
        description: 'Updated Description',
        quantity: 99,
        price: 23145
      };

      expect(productsRepository.updateById(id, updatedData)).rejects.toThrow();
    })

    it('it cannot update a product using wrong information', async() => {
      let id = products[0].id;

      const updatedData = {
        name: 'Updated Project',
        description: 'Updated Description',
        quantity: -22,
        price: -33122
      };

      expect(productsRepository.updateById(id, updatedData)).rejects.toThrow();
    });
  });

  describe('Delete a product', () => {
    it('it can delete a product', async() => {
      const data = {
        name: 'GOAT Product',
        description: 'Goat Product Description',
        quantity: 20,
        price: 20000
      };

      const product = await productsService.create(data);
      
      const spy = jest.spyOn(productsRepository, 'deleteById');
      await productsService.deleteById(product.id.value);

      expect(spy).toHaveBeenCalledTimes(1);
    });
    
    it('it cannot delete a product that does not exist', async() => {
      const data = {
        name: 'GOAT Product',
        description: 'Goat Product Description',
        quantity: 20,
        price: 20000
      };

      const product = await productsService.create(data);
      
      expect(productsService.deleteById(product.id.value + '1')).rejects.toThrow();
    })
  });
});
