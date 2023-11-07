export class NotesByStatus {
  public total!: number;
}

export enum statusListName {
  em_processamento = 'NOTAS EM PROCESSAMENTO',
  rejeitadas = 'NOTAS REJEITADAS',
  sob_balanco = 'NOTAS SOB BALANÇO',
  validas = 'NOTAS VÁLIDAS',
}

export enum statusListColors {
  em_processamento = '#FBC191',
  rejeitadas = '#FD8D8E',
  validas = '#8BE2BB',
  sob_balanco = '#61A0A8',
}

export enum ecomembershipsRolesNames {
  liable = 'Entidades Representativas',
  intermediate = 'Empresas Aderentes',
  operator = 'Operadores Logísticos',
  recycler = 'Recicladoras',
}

export enum ecomembershipsRolesIcons {
  liable = 'communication.svg',
  intermediate = 'domain.svg',
  operator = 'pie_chart.svg',
  recycler = 'recycling_symbol.svg',
}

export enum ecomembershipsRolesBGColor {
  liable = 'bg-dark',
  intermediate = 'bg-warning',
  operator = 'bg-danger',
  recycler = 'bg-black',
}
