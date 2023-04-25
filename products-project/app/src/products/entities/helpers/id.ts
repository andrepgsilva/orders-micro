import { v4 as uuidv4 } from 'uuid';

export class Id {
  private _value: string;
  
  constructor(value: string) {
    if (! Id.isValid(value)) {
      throw new Error('Invalid id value');
    }

    this._value = value;
  }

  get value() {
    return this._value;
  }

  equals(other: Id) {
    return other instanceof Id && this.value === other.value;
  }

  static generate() {
    return new Id(uuidv4());
  }

  static isValid(value: string) {
    const regex = /^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;
    return typeof value === 'string' && regex.test(value);
  }
}