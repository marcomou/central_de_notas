import { Pipe, PipeTransform } from '@angular/core';

/*
 * Examples of usage:
 *
 * ... <span>{{ 4815.162342 | numeri }}</span>
 *  => <span>4815,16</span>
 *
 * ... <span>{{ 4815.162342 | numeri:1 }}</span>
 *  => <span>4815,2</span>
 *
 * ... <span>{{ 4815.162342 | numeri:3:'ceil' }}</span>
 *  => <span>4815,163</span>
 *
 * ... <span>{{ 4815.162342 | numeri:1:'floor' }}</span>
 *  => <span>4815,1</span>
 *
 * ... <span>{{ 9001 | numeri }}</span>
 *  => <span>9001,00</span>
 */
@Pipe({
  name: 'numeri'
})
export class NumeriPipe implements PipeTransform {

  transform(value: number, decimal_places: number = 2, apply: string|null = null): string {
    if (['ceil', 'floor'].includes(apply)) {
        let power = Math.pow(10, decimal_places);
        value = Math[apply](value * power + Number.EPSILON) / power;
    }
    return value.toFixed(decimal_places).replace('.', ',');
  }

}
