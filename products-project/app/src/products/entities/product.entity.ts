import { Id } from './helpers/id';

export class Product {
  private _id: Id;
  name: string;
  description: string;
  quantity: number;
  price: number;
  createdAt: Date;
  updatedAt: Date;

  constructor(
    name: string,
    description: string,
    quantity: number,
    price: number,
    id?: string | Id,
    createdAt?: Date,
    updatedAt?: Date,
  ) {
    this.checkIfPropertiesArePositive([price, quantity]);
    this.checkIfStringPropertiesAreNotEmpty([name, description]);

    this._id = this.setProductId(id);
    this.name = name;
    this.description = description;
    this.quantity = quantity;
    this.price = price;
    this.createdAt = createdAt ?? new Date();
    this.updatedAt = updatedAt ?? new Date();
  }

  get id() {
    return this._id;
  }

  private setProductId(id: any) {
    if (id !== undefined && id instanceof Id) {
      return id;
    }

    if (Id.isValid(id)) {
      return new Id(id);
    }

    return Id.generate();
  }

  private checkIfPropertiesArePositive(props: Number[]) {
    if (props.some((prop) => prop < 0)) {
      throw new Error('The properties need to be greater than zero');
    }

    return true;
  }

  private checkIfStringPropertiesAreNotEmpty(props: String[]) {
    if (props.some((prop) => prop === '')) {
      throw new Error('The properties cannot be empty');
    }

    return true;
  }
}
