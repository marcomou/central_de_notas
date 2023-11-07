import { InvoiceFile } from './invoice-file';
import { InvoiceItem } from './invoice-item';
import { StatusFile } from './status-file.interface';

export interface SefazStatusListInterface {
  authorized: string;
  not_processed: string;
  canceled_at_sefaz: string;
  differences_in_invoice_fields: string;
  differences_in_invoice_items: string;
  data_not_found_in_origin: string;
  params_refused_by_origin: string;
}

export interface OutlierStatusListInterface {
  invalid: string;
  valid: string;
}

export class Invoice {
  status!: keyof StatusFile;
  issuer: any;
  issuer_name!: string;
  operation_type!: number;
  issuer_taxid!: number;
  access_key!: string;
  invoice_items!: Array<InvoiceItem>;
  recipient_name!: string;
  recipient_taxid!: number;
  sefaz_status?: 'authorized' | 'not_processed' | 'canceled_at_sefaz' | 'differences_in_invoice_fields' | 'differences_in_invoice_items' | 'data_not_found_in_origin' | 'params_refused_by_origin';
  outlier_status?: 'valid'|'invalid';

  static sefazStatusList: SefazStatusListInterface = {
    authorized: "Nota autorizada para uso.",
    // not_processed: "Nota precisa ser reprocessada. Em avaliação interna.",
    not_processed: "Nota precisa ser processada na sefaz.",
    canceled_at_sefaz: "Nota cancelada pela sefaz.",
    differences_in_invoice_fields: "Os dados da nota são diferentes dos dados encontrados na sefaz.",
    differences_in_invoice_items: "A quantidade de itens de nota é diferente da quantidade de itens encontrada na sefaz.",
    data_not_found_in_origin: "Nota não encontrada na sefaz.",
    params_refused_by_origin: "A chave da nota enviada possui algum erro e não foi aceita como parametro de busca na sefaz.",
  };
  static outlierStatusList = {
    invalid: "Reprovado na Análise Estatística.",
    valid: "Aprovado na Análise Estatística.",
  };

  static status: {[k:string]: string} = {
    // validated: 'Nota Validada',
    // valid: 'Nota Validada',
    // rejected: 'Reciclador não homologado',
    // invalid: 'Nota Inválida',
    // carried: 'Bloqueada por Entrada não comprovada',
    // tracked: 'Rastreada para outro operador',
    // registered: 'Nota Em Validação',
    // collidence: 'Nota Bloqueada',
    // considered: 'Entrada Considerada',
    invalid: 'Nota Inválida',
    registered: 'Nota Validada',
    sefazNotProcessed: 'Nota Em Validação',
    collidence: 'Nota Bloqueada',
  };

  public static colors: {[k:string]: string} = {
    invalid: '#f4c7c3',
    collidence: '#f4c7c3',
    sefazNotProcessed: '#fce8b2',
    // registered: '#fce8b2',
    registered: '#b7e1cd',
    validated: '#b7e1cd',
  };

  static operationTypes = {
    input: 'Entrada',
    output: 'Saída',
  };

  constructor(properties?: { [k: string]: any }) {
    if (properties) {
      this.status = properties['status'] || null;
      this.issuer = properties['issuer'] || null;
      this.issuer_name = properties['issuer_name'] || (properties['issuer'] && properties['issuer']['name']) || null;
      this.operation_type = properties['operation_type'] ?? null;
      this.issuer_taxid = properties['issuer_taxid'] || null;
      this.access_key = properties['access_key'] || null;
      this.invoice_items = properties['invoice_items'] || null;
      this.recipient_name = properties['recipient_name'] || (properties['recipient'] && properties['recipient']['name']) || null;
      this.recipient_taxid = properties['recipient_taxid'] || null;
      this.sefaz_status = properties['sefaz_status'] || null;
      this.outlier_status = properties['outlier_status'] || null;
    }
  }

  get isInvalid() {
    return this.status === 'invalid';
  }

  get sefazNotProcessed() {
    // return !this.sefaz_status || this.sefaz_status === 'not_processed';
    return false;
  }

  getStatus() {
    if (this.status === 'collidence') {
      return Invoice.status['collidence'];
    }

    if (this.sefazNotProcessed) {
      return Invoice.status['sefazNotProcessed'];
    }

    if (Invoice.status.hasOwnProperty(this.status)) {
      return Invoice.status[this.status];
    }

    return Invoice.status['registered'];
  }

  getStatusColor() {
    if (this.status === 'collidence') {
      return Invoice.colors['collidence'];
    }

    if (this.sefazNotProcessed) {
      return Invoice.colors['sefazNotProcessed'];
    }

    if (Object.keys(Invoice.colors).includes(this.status)) {
      return Invoice.colors[this.status];
    }

    return Invoice.colors['registered'];
  }

  getStatusMessage() {
    const collidenceMessage = InvoiceFile.collidenceMessage;

    if (this.status === 'collidence') {
      return collidenceMessage;
    }

    const sefazStatus = this.sefaz_status as keyof SefazStatusListInterface | undefined;
    if (sefazStatus && sefazStatus !== 'authorized') {
      return Invoice.sefazStatusList[sefazStatus];
    }

    const outlierStatus = this.outlier_status as keyof OutlierStatusListInterface | undefined;
    if (outlierStatus && outlierStatus !== 'valid') {
      return Invoice.outlierStatusList[outlierStatus];
    }

    if (this.status === 'invalid') {
      return "Reprovado na Análise Estatística";
    }

    return '';
  }

  getOperationType() {
    switch (this.operation_type) {
      case 0:
        return Invoice.operationTypes.input;
      case 1:
        return Invoice.operationTypes.output;
    }

    return '';
  }
}
