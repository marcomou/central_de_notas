export class PhoneMaskProvider {

  private static readonly _nonDigitsPattern = /[^\d]/g;

  private static _11_DigitsPhoneMask = ['(', /\d/, /\d/, ')', ' ', /\d/, /\d/, /\d/, ' ', /\d/, /\d/, /\d/, ' ', /\d/, /\d/, /\d/];
  private static _10_DigitsPhoneMask = ['(', /\d/, /\d/, ')', ' ', /\d/, /\d/, /\d/, /\d/, ' ', /\d/, /\d/, /\d/, /\d/];
  // private static _09_DigitsPhoneMask = [/\d/, /\d/, /\d/, ' ', /\d/, /\d/, /\d/, ' ', /\d/, /\d/, /\d/];
  // private static _08_DigitsPhoneMask = [/\d/, /\d/, /\d/, /\d/, ' ', /\d/, /\d/, /\d/, /\d/];
  
  public provide(input: string = ''): Array<any> {
    let len = input.length;
    let dlen = input.replace(PhoneMaskProvider._nonDigitsPattern, '').length;
    let mask = PhoneMaskProvider._11_DigitsPhoneMask;
    // if (dlen ===  8) { mask = PhoneMaskProvider._08_DigitsPhoneMask; }
    // if (dlen ===  9) { mask = PhoneMaskProvider._09_DigitsPhoneMask; }
    if (dlen === 10) { mask = PhoneMaskProvider._10_DigitsPhoneMask; }
    return mask;
  }
}
