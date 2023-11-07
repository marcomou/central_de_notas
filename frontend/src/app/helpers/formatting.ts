import { formatDate } from '@angular/common';

export class FormatCPF {

  public static in(input: string): string {
    let m = input.match(this._regexp);
    return m ? (m[1] + "." + m[2] + "." + m[3] + "-" + m[4]) : input;
  }

  private static _regexp = /(\d{3})(\d{3})(\d{3})(\d{2})/;
}

export class FormatCNPJ {

  public static in(input: string): string {
    let m = input.match(this._regexp);
    return m ? (m[1] + "." + m[2] + "." + m[3] + "/" + m[4] + "-" + m[5]) : input;
  }

  private static _regexp = /(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/;
}


export class ConvertToFloat {
  public static convert(arg: string | number): number {
    return parseFloat(arg.toString().replace(',', '.'));
  }

  public static reverse(arg: number, decimals = 2): string {
    return arg.toFixed(decimals).replace('.', ',');
  }
}

export class FormatToDatetimeLocal {
  public static convert(date: string) {
    return date.replace(' ', 'T');
  }

  public static reverse(date: string) {
    return date.replace('T', ' ');
  }
}

export class DateTimeLocalConversion {
  public static fromDateToClientInputString(dt: Date) {
    return formatDate(dt, 'yyyy-MM-dd HH:mm:ss', 'pt-BR').replace(' ', 'T');
  }

  public static fromDateToServerSqlString(dt: Date, offset_hours_from_server_to_client: number = 0) {
    const offset_minutes = offset_hours_from_server_to_client * 60 + dt.getTimezoneOffset();
    const offset_miliseconds = offset_minutes * 60 * 1000;
    // if server is on GMT (+00:00), offset_miliseconds should turn out to be zero

    const adjusted_to_server_dt = new Date(dt.getTime() - offset_miliseconds);
    return adjusted_to_server_dt.toISOString().replace('T', ' ').substr(0, 19);
  }
}