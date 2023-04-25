export class ProductNotFoundError extends Error {
  name: string;

  constructor(message = 'Product was not found') {
    super();
    this.message = message;
  }
}