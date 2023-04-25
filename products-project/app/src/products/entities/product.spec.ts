import { Id } from './helpers/id';
import { Product } from './product.entity';

describe('ProductEntity', () => {
  describe('Create a product entity', () => {
    it('should create a entity with a random uuid', () => {
      const product = new Product(
        'Awesome ', 
        'This is an awesome product', 
        91, 
        90000
      );

      expect(product.id).toBeInstanceOf(Id);
    });

    it('should create a entity with a provided uuid', () => {
      const product = new Product(
        'Awesome Product', 
        'This is an awesome product', 
        91, 
        90000,
        Id.generate()
      );

      expect(product.id).toBeInstanceOf(Id);
    });
    
    it('should not create a entity with a name or description empty', () => {
      const data = {
        name: '',
        description: '',
        quantity: 20,
        price: 3,
      };

      expect(() => {
        new Product(data.name, data.description, data.quantity, data.price);
      }).toThrow();
    });

    it('should not create a entity with a negative quantity/price', () => {
      const data = {
        name: 'Great Product',
        description: 'This is a great product',
        quantity: -2,
        price: -3,
      };

      expect(() => {
        new Product(data.name, data.description, data.quantity, data.price);
      }).toThrow();
    });
  });
});
