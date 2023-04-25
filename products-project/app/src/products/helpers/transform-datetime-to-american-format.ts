export class TransformDatetimeToAmericanFormat {
  static execute(date?: Date) {
    if (date === undefined) {
      date = new Date();
    }
    
    return date.toISOString().slice(0, 19).replace('T', ' ');
  }
}