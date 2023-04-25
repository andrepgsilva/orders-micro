import { ApiProperty } from "@nestjs/swagger";

export class CreateProductSwagger {
  @ApiProperty()
  id: string;

  @ApiProperty()
  name: string;

  @ApiProperty()
  description: string;

  @ApiProperty()
  quantity: number;

  @ApiProperty()
  price: number;

  @ApiProperty()
  created_at: string;
  
  @ApiProperty()
  updated_at: string;
}