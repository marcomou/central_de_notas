import { Component, ContentChild, Input, OnInit } from '@angular/core';
import { InputRefDirective } from 'src/app/directives/input-ref.directive';

@Component({
  selector: 'nhe-input',
  templateUrl: './nhe-input.component.html',
  styleUrls: ['./nhe-input.component.scss'],
})
export class NheInputComponent implements OnInit {
  @ContentChild(InputRefDirective)
  input!: InputRefDirective;

  constructor() {}

  @Input()
  id: string = 'input-' + Math.random().toString();

  @Input()
  label?: string;

  @Input()
  validations: { [index: string]: string } = {
    required: 'Campo obrigatório.',
    pattern: 'Erro de validação. O dado inserido está mal formatado.',
    minlength: 'Precisa ter no mínimo %s caracteres.',
    email: 'Use um email válido.',
    cpf: 'Use um CPF válido.',
    cnpj: 'Use um CNPJ válido.',
    isAlphaNumeric: 'Este campo precisa ter no mínimo uma letra, um número, e um caracter especial.',
  };

  disabled: boolean = false;

  ngOnInit(): void {}

  get hasError() {
    return this.input?.hasError;
  }

  get isRequired() {
    if (!this.input || this.input.formControl?.valid) {
      return false; // if the input is already valid, it could not appear the red '*'
    }

    const validator = this.input.formControl?.validator;
    if (!validator) {
      return false;
    }
    const validators = validator(this.input?.formControl?.control?.value);
    if (!validators) {
      return false;
    }
    return Object.keys(validators).indexOf('required') !== -1;
  }

  get errorMessages() {
    const errors: any = this.input.errors;
    const messages: any[] = [];
    const keys = Object.keys(this.validations);

    if (errors['required']) {
      return [this.validations['required']];
    }

    keys.forEach((key) => {
      if (errors[key]) {
        let message = this.validations[key];

        if (key === 'minlength') {
          message = this.validations[key].replace('%s', errors[key].requiredLength);
        }

        messages.push(message);
      }
    });
    return messages;
  }
}
