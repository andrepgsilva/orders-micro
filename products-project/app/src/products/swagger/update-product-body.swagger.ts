import { PartialType } from "@nestjs/swagger";
import { CreateProductDto } from "../dto/create-product.dto";

export class UpdateProductBodySwagger extends PartialType(CreateProductDto) {}