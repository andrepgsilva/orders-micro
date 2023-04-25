import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { HydratedDocument } from 'mongoose';

export type ProductDocument = HydratedDocument<ProductModel>;

@Schema({ collection: 'products' })
export class ProductModel {
  @Prop()
  _id: string

  @Prop()
  name: string;

  @Prop()
  description: string;

  @Prop()
  quantity: number;

  @Prop()
  price: number;

  @Prop({ name: 'created_at' })
  createdAt: Date;

  @Prop({ name: 'updated_at'})
  updatedAt: Date;
}

export const ProductSchema = SchemaFactory.createForClass(ProductModel);