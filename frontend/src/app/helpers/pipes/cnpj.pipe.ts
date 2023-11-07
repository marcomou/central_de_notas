import { Pipe, PipeTransform } from '@angular/core';

import { FormatCNPJ } from 'src/app/helpers/formatting';

@Pipe({
  name: 'cnpj'
})
export class CnpjPipe implements PipeTransform {

  transform(str: string): string {
    return FormatCNPJ.in(str);
  }

}
