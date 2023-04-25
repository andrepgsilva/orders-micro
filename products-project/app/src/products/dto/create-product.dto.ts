import { ApiProperty } from "@nestjs/swagger"
import { IsNotEmpty, IsNumber, Min } from "class-validator"

export class CreateProductDto {
  @IsNotEmpty()
  @ApiProperty()
  name: string

  @IsNotEmpty()
  @ApiProperty()
  description: string

  @IsNotEmpty()
  @IsNumber()
  @Min(0)
  @ApiProperty()
  quantity: number

  @IsNotEmpty()
  @IsNumber()
  @Min(0)
  @ApiProperty()
  price: number
}