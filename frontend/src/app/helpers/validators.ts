import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';

export class CnpjValidator {
  private static readonly _nonDigitsPattern = /[^\d]/g;
  private static readonly _cnpjPattern = /^(\d{12})\d{2}$/;
  private static readonly _cnpjModulo11Cap = 9;

  public static test(cnpj: string|AbstractControl = ''): ValidationErrors|boolean {
    let invalidReturnType: false|ValidationErrors = false;
    let sequence;

    if (cnpj instanceof AbstractControl) {
      sequence = cnpj.value as string;
      invalidReturnType = { cnpj: { value: cnpj.value } };
    } else {
      sequence = cnpj;
    }

    sequence = sequence.replace(CnpjValidator._nonDigitsPattern, '');

    if (sequence.length && !sequence.match(/^(.)\1*$/)) {
      let matched = sequence.match(CnpjValidator._cnpjPattern);

      if (matched) {
        let newSequence = matched[1];

        newSequence += dv_modulo_11(
          newSequence,
          CnpjValidator._cnpjModulo11Cap
        );

        newSequence += dv_modulo_11(
          newSequence,
          CnpjValidator._cnpjModulo11Cap
        );

        if (sequence === newSequence) {
          return true;
        }

        return invalidReturnType;
      }

      return invalidReturnType;
    }

    return invalidReturnType;
  }
}

export class CpfValidator {
  private static readonly _nonDigitsPattern = /[^\d]/g;
  private static readonly _cpfPattern = /^(\d{9})\d{2}$/;
  private static readonly _cpfModulo11Cap = 11;

  public static test(cpf: string|AbstractControl = ''): ValidationErrors|boolean {
    let invalidReturnType: false|ValidationErrors = false;
    let sequence;

    if (cpf instanceof AbstractControl) {
      sequence = cpf.value as string;
      invalidReturnType = { cpf: { value: cpf.value } };
    } else {
      sequence = cpf;
    }

    sequence = sequence.replace(CpfValidator._nonDigitsPattern, '');

    if (sequence.length) {
      let matched = sequence.match(CpfValidator._cpfPattern);

      if (matched) {
        let newSequence = matched[1];
        newSequence += dv_modulo_11(newSequence, CpfValidator._cpfModulo11Cap);
        newSequence += dv_modulo_11(newSequence, CpfValidator._cpfModulo11Cap);

        if (sequence === newSequence) {
          return true;
        };

        return invalidReturnType;
      }

      return invalidReturnType;
    }

    return invalidReturnType;
  }
}

export class EmailValidator {
  public static test(str: string = '') {
    return str.match(/^[^\@]+@[^\@]+$/);
  }
}

function dv_modulo_11(sequence: string, cap: number) {
  if (!sequence.match(/^\d+$/)) {
    return '';
  }
  let length = sequence.length;
  let digits = sequence.split('').map((d) => parseInt(d));
  let upto = cap - 1;
  let total = digits.reduce((carry, _ignore, index) => {
    let digit = digits[length - 1 - index];
    let decay = index - (index % upto);
    let factor = 2 + index - decay;
    return carry + factor * digit;
  }, 0);
  let remainder = total % 11;
  return remainder < 2 ? 0 : 11 - remainder;
}

export function validPassword(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const isAlphaNumeric =
      !/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]/.test(
        control.value
      );
    return isAlphaNumeric ? { isAlphaNumeric: { value: control.value } } : null;
  };
}

export function isAlphaNumericString(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const isAlphaNumeric =
      !/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]/.test(
        control.value
      );
    return isAlphaNumeric ? { isAlphaNumeric: { value: control.value } } : null;
  };
}
