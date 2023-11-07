import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap/modal';
import { InvoiceFile } from 'src/app/models/invoice/invoice-file';
import { OrganizationService } from 'src/app/services/organization.service';

@Component({
  selector: 'app-invoice-detail-page',
  templateUrl: './invoice-detail-page.component.html',
  styleUrls: ['./invoice-detail-page.component.scss'],
})
export class InvoiceDetailPageComponent implements OnInit {
  public invoiceFile!: InvoiceFile;
  public invoice!: any;
  public form: FormGroup;
  public deleteInvoiceFile: (invoiceFileGuid: string) => void = () => {};

  constructor(
    private organizationService: OrganizationService,
    private formBuilder: FormBuilder,
    private bsModalRef: BsModalRef
  ) {
    this.form = this.formBuilder.group({});
  }

  ngOnInit(): void {
    if (this.invoiceFile.invoiceAccessKey) {
      this.getInvoiceDetail(this.invoiceFile.invoiceAccessKey);
    }
  }

  get hasNotRegulatoryBody(): boolean {
    return !this.organizationService.currentRegulatoryBody;
  }

  getInvoiceDetail = (invoiceId: string) => {
    const organizationId = this.organizationService.currentOrganization.id;
    this.organizationService
      .getInvoiceDetails(organizationId, invoiceId)
      .subscribe({
        next: (response: any) => (this.invoice = response.data),
        error: (error: any) => console.log(error),
      });
  };

  close = () => {
    this.bsModalRef.hide();
  };

  getInvoiceStatus = (
    invoiceStatus: string
  ): { value: string; class: string } => {
    // switch (invoiceStatus) {
    //   case 'registered':
    //     return { value: 'Validando nota', class: 'warning' };
    //   case 'blocked':
    //     return { value: 'Aguardando balanço', class: 'danger' };
    //   case 'chained':
    //     return { value: 'Rastreando', class: 'default' };
    //   case 'matched':
    //     return { value: 'Utilizado', class: 'danger' };
    //   case 'validated':
    //     return { value: 'Aguardando compensação', class: 'danger' };
    //   case 'rejected':
    //     return { value: 'Reciclador não homologado', class: 'danger' };
    //   case 'invalid':
    //     return { value: 'Nota Cancelada', class: 'danger' };
    //   case 'carried':
    //     return {
    //       value: 'Nota bloqueada por entrada não comprovada',
    //       class: 'danger',
    //     };
    //   case 'tracked':
    //     return { value: 'Nota rastreada para outro operador', class: 'danger' };
    //   case 'collidence':
    //     return { value: 'Nota colidente', class: 'danger' };
    //   case 'considered':
    //     return { value: 'Nota considerada', class: 'default' };
    //   default:
    //     return { value: 'Validando nota', class: 'danger' };
    // }

    /////////////////////////////////////////////////////////////////////////////////////////

    switch (invoiceStatus) {
      case 'invalid':
        return { value: 'Reprovado na Análise Estatística', class: 'danger' };
      case 'collidence':
        return { value: 'Nota Bloqueada', class: 'danger' };
      default:
        return { value: 'Nota Bloqueada', class: 'danger' };
    }
  };

  public giveUpTheDispute(): void {
    this.bsModalRef.onHide?.subscribe(() => this.deleteInvoiceFile(this.invoiceFile.guid));
    this.close();
  }
}
