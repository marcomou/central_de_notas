export function numberFormatCommaString(anyNumber: number): string{
  return anyNumber.toString().replace('.', ',');
}

export function showMassNumberFormatter(num: number){
  const numbersAfterComma = 2
  return Number(num.toFixed(numbersAfterComma))
}
