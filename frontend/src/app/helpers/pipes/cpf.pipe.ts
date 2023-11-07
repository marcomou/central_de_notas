import { Pipe, PipeTransform } from '@angular/core';

import { FormatCPF } from 'src/app/helpers/formatting';

@Pipe({
  name: 'cpf'
})
export class CpfPipe implements PipeTransform {

  transform(str: string): string {
    return FormatCPF.in(str);
  }

}
