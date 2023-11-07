import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'truncate'
})

export class TruncatePipe implements PipeTransform {

  transform(str: string, args: string[]): string {

    const size = parseInt(args[0], 10) || 20;
    const pos = args[1] || 'default';
    const trail = args[2] || '\u2026';

    var s;
    if (str.length <= size) {
      s = str;
    }
    else if (pos === 'midfix') {
      var hs = Math.floor(size / 2);
      var lmhs = str.length - hs;
      s = str.substring(0, hs) + trail + str.substring(lmhs);
    }
    else if (pos === 'prefix') {
      s = trail + str.substring(str.length - size);
    }
    else {
      s = str.substring(0, size) + trail;
    }
    return s;

  }
}