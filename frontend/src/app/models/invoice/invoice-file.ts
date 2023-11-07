import { Invoice } from './invoice';

export interface StatusTranslatedInterface {
  "File doesn't have InvoiceData": string;
  "File type is not supported": string;
  "File access key belongs to Closed Ecoduty": string;
  "could not read file": string;
};

export class InvoiceFile {
  static statuses = {
    0: 'Arquivo Em Validação',
    1: 'Arquivo Inválido',
    2: 'Arquivo Duplicado',
    3: 'Arquivo Validado',
    // 4: 'Colidente com outro programa',
    4: 'Arquivo Validado', //
  };

  static collidenceMessage = "Nota Colidida";

  public colors = {
    rejected: '#f4c7c3',
    pending: '#fce8b2',
    success: '#b7e1cd',
  };

  static statusTranslated: StatusTranslatedInterface = {
    "File doesn't have InvoiceData": "Arquivo (XML) não possui dados de nota fiscal.",
    "File type is not supported": "Formato do arquivo é diferente de XML.",
    "File access key belongs to Closed Ecoduty": "Nota utilizada em um outro período de envio.",
    "could not read file": "Erro interno na leitura do arquivo. Envie novamente.",
  };

  public ecoduty: any;
  public email_guid: any;
  public file_location!: string;
  public file_type!: string;
  public filename!: string;
  public gatherer: any;
  public gatherer_guid!: string;
  public guid!: string;
  public handled_with!: string;
  public invoices!: Array<Invoice>;
  public invoice?: Invoice;
  public private_uri!: string;
  public public_uri!: string;
  public reading_duration_ms!: number;
  public status!: number;
  public status_reason!: string;
  public tries_counter!: number;
  public aggregated_material_mass: any;
  public collidence!: boolean;

  constructor(properties?: { [k: string]: any }) {
    if (properties) {
      this.ecoduty = properties['ecoduty'] || null;
      this.email_guid = properties['email_guid'] || null;
      this.file_location = properties['file_location'] || null;
      this.file_type = properties['file_type'] || null;
      this.filename = properties['filename'] || null;
      this.gatherer = properties['gatherer'] || null;
      this.gatherer_guid = properties['gatherer_guid'] || null;
      this.guid = properties['guid'] || null;
      this.handled_with = properties['handled_with'] || null;
      this.invoices = properties['invoices'] || null;
      this.invoice = properties['invoice']
        ? new Invoice(properties['invoice'])
        : undefined;
      this.private_uri = properties['private_uri'] || null;
      this.public_uri = properties['public_uri'] || null;
      this.reading_duration_ms = properties['reading_duration_ms'] || null;
      this.status = properties['status'] || null;
      this.status_reason = properties['status_reason'] || null;
      this.tries_counter = properties['tries_counter'] || null;
      this.aggregated_material_mass =
        properties['aggregated_material_mass'] || null;
      this.collidence = properties['collidence'] || null;
    }
  }

  aggregatedMaterialsToString(decimals: number = 2): string {
    return (
      Object.keys(this.aggregated_material_mass || {})
        .map((materialKey) => {
          return (
            materialKey +
            ': ' +
            ((this.aggregated_material_mass[materialKey] || 0) * 0.001).toFixed(
              decimals
            ) +
            't'
          );
        })
        .join(', ') || ' - '
    );
  }

  get issuerName() {
    return (this.invoice && this.invoice.issuer_name) || null;
  }

  get recipientName() {
    return (this.invoice && this.invoice.recipient_name) || null;
  }

  get issuerTaxid() {
    return (this.invoice && this.invoice.issuer_taxid) || null;
  }

  get recipientTaxid() {
    return (this.invoice && this.invoice.recipient_taxid) || null;
  }

  get invoiceAccessKey() {
    return (this.invoice && this.invoice.access_key) || null;
  }

  get hasStatusMessage() {
    return !!this.getStatusMessage();
  }

  getStatus() {
    // if (this.collidence) {
    //   return Invoice.status['collidence'];
    // }

    // const statusList = [0, 1, 2, 4];
    const statusList = [0, 1, 2, 3, 4];
    if (statusList.includes(this.status)) {
      return InvoiceFile.statuses[this.status as 0 | 1 | 2 | 4];
    }

    // if (this.status === 3) {
    //   if (this.invoice) {
    //     if (this.invoice.status === 'invalid') {
    //       return Invoice.status['invalid'];
    //     }

    //     if (this.invoice.status === 'collidence') {
    //       return Invoice.status['collidence'];
    //     }
    //   }

    //   return Invoice.status['validated'];
    // }

    return '-';
  }

  getInvoiceStatus() {
    return (
      (this.collidence
        ? Invoice.status['collidence']
        : this.invoice?.getStatus()) || null
    );
  }

  getStatusColor() {
    // const statusList = [1, 2, 4];
    const statusDefault = this.colors.success;
    const statusList: {[status: number]: string} = {
      0: this.colors.pending,
      1: this.colors.rejected,
      2: this.colors.rejected,
      3: this.colors.success,
      4: this.colors.success,
    }

    // if (this.collidence) {
    //   return this.colors.rejected;
    // }

    return statusList[this.status] || statusDefault;
  }

  getStatusMessage() {
    const collidenceMessage = InvoiceFile.statuses[4];

    if ([1, 2].includes(this.status)) {
      let message = this.status_reason;

      Object.keys(InvoiceFile.statusTranslated).forEach((status) => {
        if (this.status_reason.includes(status)) {
          message = InvoiceFile.statusTranslated[status as keyof StatusTranslatedInterface];
        }
      });

      return message;
    }

    // if (this.status === 4 || this.collidence) {
    //   return collidenceMessage;
    // }

    return '';
  }

  protected static setonce_properties = [];

  protected static modifiable_properties = [];
}
