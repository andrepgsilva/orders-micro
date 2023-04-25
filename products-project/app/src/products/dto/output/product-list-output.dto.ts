import { Product } from "src/products/entities/product.entity";
import { TransformDatetimeToAmericanFormat } from "../../helpers/transform-datetime-to-american-format";
import { ProductOutput } from "../types/product-output.type";

export class ProductListOutputDto {
  static transform(products: Product[]) {
    return products.map(product => {
      return { 
        id: product.id.value,
        name: product.name,
        description: product.description,
        quantity: product.quantity,
        price: product.price,
        created_at: TransformDatetimeToAmericanFormat.execute(product.createdAt),
        updated_at: TransformDatetimeToAmericanFormat.execute(product.updatedAt),
      } as ProductOutput;
    })
  }  
}