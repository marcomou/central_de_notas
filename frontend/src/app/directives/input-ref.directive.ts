import { Directive } from '@angular/core';
import { NgControl, ValidationErrors } from '@angular/forms';

@Directive({
  selector: '[appInputRef]',
})
export class InputRefDirective {
  constructor(public formControl: NgControl) {}

  get hasError(): boolean | null {
    return (
      (this.formControl.dirty || this.formControl.touched) &&
      this.formControl.invalid
    );
  }

  get errors(): ValidationErrors | '' {
    if (this.hasError && this.formControl.errors) {
      return this.formControl.errors;
    }
    return '';
  }
}
