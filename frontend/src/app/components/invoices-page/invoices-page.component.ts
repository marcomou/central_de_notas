import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { BsModalService } from 'ngx-bootstrap/modal';
import { InvoiceFile } from 'src/app/models/invoice/invoice-file';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { OrganizationService } from 'src/app/services/organization.service';
import { environment } from 'src/environments/environment';
import { FileUploadModelComponent } from '../file-upload-model/file-upload-model.component';
import { BlockedNotesModalComponent } from '../blocked-notes-modal/blocked-notes-modal.component';
import { InvoiceDetailPageComponent } from '../invoice-detail-page/invoice-detail-page.component';
import { Offcanvas } from 'bootstrap';
import * as xml2js from 'xml2js';
import { Invoice } from 'src/app/models/invoice/invoice';
import { AlertComponent } from '../alert/alert.component';

class Filters {
  file_status?: string;
  skip?: number;
  operation?: number;
  invoice_status?: string;
  material: string = '';
  getherer?: string;
  eco_duties?: string;
  issuer_name: string = '';
  issuer_taxid: string = '';
  recipient_taxid: string = '';
  recipient_name: string = '';
  search: string = '';
}
@Component({
  selector: 'app-invoices-page',
  templateUrl: './invoices-page.component.html',
  styleUrls: ['./invoices-page.component.scss'],
})
export class InvoicesPageComponent implements OnInit {
  public invoiceFiles: InvoiceFile[] = [];
  public skip: number = 0;
  public top: number = 10;
  public total: number = 0;
  public statusList: any[] = [
    {id: 'registered', value: Invoice.status['registered']},
    {id: 'invalid', value: Invoice.status['invalid']},
    {id: 4, value: Invoice.status['collidence']},
    {id: 0, value: InvoiceFile.statuses[0]},
    {id: 1, value: InvoiceFile.statuses[1]},
    {id: 3, value: InvoiceFile.statuses[3]},
  ];
  public materialTypes!: any[];
  public takerList!: any[];
  public receptorList!: any[];
  public statusFilter?: number;
  public filters: any = new Filters();
  public icon = environment.nhe_icons.file_download;
  public invoiceStatus = '';
  public acceptTypes: string[] = ['xml'];
  public loading = false;
  public currentPage = 1;

  @ViewChild('filterSidebar') filterSidebar!: ElementRef;

  constructor(
    private organizationService: OrganizationService,
    private ecoDutyService: EcoDutyService,
    private modalService: BsModalService
  ) {}

  ngOnInit(): void {
    this.getInvoices();
    this.getMaterialTypes();
    // this.getTomador();
    // this.getReceptor();
  }

  ngAfterViewInit() {
    new Offcanvas(this.filterSidebar.nativeElement, { backdrop: true });
  }

  get hasManagingEntitySelected() {
    return !!this.organizationService.currentManagingEntity;
  }

  get invoiceColorDefault() {
    return Invoice.status['registered'];
  }

  public getInvoices = (params: any = {}) => {
    if (params.skip === undefined) {
      this.currentPage = 1;
    }

    // const currentRegulatoryBody = this.organizationService.currentRegulatoryBody;
    // const getherer = this.organizationService.currentManagingEntity?.getherer;
    // if (getherer) {
    //   params.getherers = getherer;
    // }
    // TODO: add getherers list of managing entities when has currentRegulatoryBody
    // if (getherers) {
    //  $params = 
    //}

    params.operation = 1;

    // const ecoDuty = this.ecoDutyService.currentEcoDuty;
    // if (ecoDuty) {
    //   params.eco_duties = ecoDuty.id;
    // }

    Object.keys(this.filters).forEach(filterKey => {
      let value = this.filters[filterKey];
      if (typeof value === 'string') {
        value = value.trim();
      }

      if (value || value === 0) {
        params[filterKey] = value;
      }
    });

    if (this.invoiceStatus) {
      if (Object.keys(InvoiceFile.statuses).includes(this.invoiceStatus)) {
        params.file_status = this.invoiceStatus;
      }

      if (Object.keys(Invoice.status).includes(this.invoiceStatus)) {
        params.invoice_status = this.invoiceStatus;
      }
    }

    this.loading = true;
    this.organizationService.getInvoicesList(params).subscribe({
      next: (response) => {
        this.invoiceFiles = response.data;
        this.skip = response.skip;
        this.top = response.top;
        this.total = response.total;
        this.currentPage = (this.skip / this.top) + 1;
        this.loading = false;
      },
      error: (error) => {
        console.error(error);
        this.loading = false;
      },
    });
  };

  pageChanged = (page: number, forceLoad = false) => {
    let params: any = {};
    if (page) {
      if (this.currentPage === page && !forceLoad) {
        return;
      }
      this.currentPage = page;
    }

    params.skip = (this.currentPage - 1) * this.top;
    this.getInvoices(params);
  };

  // public getTomador = (params: Filters = {}) => {
  //   const gethererId = this.organizationService.currentOrganization.id;
  //   this.filters.getherer = gethererId;
  //   params.getherer = gethererId;
  //   params.operation = 1;
  //   this.organizationService.getInvoicesList(params).subscribe({
  //     next: (response) => {
  //       this.takerList = response.data;
  //       this.skip = response.skip;
  //       this.top = response.top;
  //       this.total = response.total;
  //     },
  //     error: (error) => console.log(error),
  //   });
  // };

  //  public getReceptor = (params: Filters = {}) => {
  //   const gethererId = this.organizationService.currentOrganization.id;
  //   this.filters.getherer = gethererId;
  //   params.getherer = gethererId;
  //   params.operation = 1;
  //   this.organizationService.getInvoicesList(params).subscribe({
  //     next: (response) => {
  //       this.receptorList = response.data;
  //       this.skip = response.skip;
  //       this.top = response.top;
  //       this.total = response.total;
  //     },
  //     error: (error) => console.log(error),
  //   });
  // }; 

  // filterByStatus(selectEvent: any) {
  //   console.log(selectEvent?.target.value);
  //   this.filters.file_status = selectEvent?.target.value;
  //   this.getInvoices(this.filters);
  // }

  // filterByMaterials(selectEvent: any) {
  //   this.filters.material = selectEvent?.target.value;
  //   console.log(this.filters.material);
  //   this.getInvoices(this.filters);
  // }

  // filterByTaker(selectEvent: any) {
  //   this.filters.issuer_taxid = selectEvent?.target.value;
  //   const listToma = this.takerList.filter(lista => (lista.invoice.issuer_taxid == this.filters.issuer_taxid));
  //   this.takerList = listToma;
  //   if(this.takerList != this.invoiceFiles) {
  //     this.invoiceFiles = this.takerList;
  //   }
  //   // this.getTomador(this.filters);
  // }

  // filterByReceptor(selectEvent: any) {
  //   this.filters.recipient_taxid = selectEvent?.target.value;
  //   const listReceptor = this.receptorList.filter(list => (list.invoice.recipient_taxid == this.filters.recipient_taxid));
  //   this.receptorList = listReceptor;
  //   if(this.receptorList != this.invoiceFiles) {
  //     this.invoiceFiles = this.receptorList;
  //   }
  //   // this.getReceptor(this.filters);
  // }

  // filterByOperation(selectEvent: any) {
  //   this.filters.operation = selectEvent?.target.value;
  //   this.getInvoices(this.filters);
  // }

  public getMaterialTypes = () => {
    this.organizationService.getMaterialTypes().subscribe({
      next: (response: any) => (this.materialTypes = response.data),
      error: (error) => console.log(error),
    });
  };

  public getMaterials(invoiceFile: InvoiceFile): string | undefined {
    return invoiceFile?.invoice?.invoice_items
      ?.map((item) => item.ncm_material)
      .join(', ');
  }
 
  public getStatus(invoiceFile: InvoiceFile): string | number {
    // const fileStatus = invoiceFile.status ?? 0;
    // return this.statusList.find(({ id }) => id === fileStatus)?.value;
    return invoiceFile?.getStatus();
  }

  public openFileUploadModal() {
    this.modalService.show(FileUploadModelComponent, {
      animated: true,
      class: 'modal-lg',
      initialState: {
        title: 'Enviar Notas Fiscais',
        acceptTypes: this.acceptTypes,
        allowMultiple: true,
        onSelected: (files) => this.uploadInvoice(files),
      },
    });
  }

  public openBlockedNotesModal(inputInvoiceList: Array<any>) {
    this.modalService.show(BlockedNotesModalComponent, {
      animated: true,
      class: 'modal-lg',
      initialState: {
        title: 'Notas Bloqueadas',
        allowMultiple: true,
        blockedNotesList: inputInvoiceList,
        onSelected: (files) => this.uploadInvoice(files),
      },
    });
  }

  public openDetail = (invoice: any) => {
    this.modalService.show(InvoiceDetailPageComponent, {
      class: 'modal-xl modal-dialog-centered',
      animated: true,
      initialState: {
        invoiceFile: invoice,
        deleteInvoiceFile: (invoiceFileGuid) => this.giveUpTheDispute(invoiceFileGuid),
      },
    });
  };

  public giveUpTheDispute(invoiceFileGuid: string): void {
    this.modalService.show(AlertComponent, {
      class: 'modal-lg modal-dialog-centered',
      animated: true,
      initialState: {
        status: 'question',
        title: 'Atenção!',
        message: 'Tem certeza que deseja desistir da disputa?',
        onPrimary: () => this.deleteInvoiceFile(invoiceFileGuid),
      }
    });
  }

  private deleteInvoiceFile(invoiceFileGuid: string): void {
    this.modalService.hide();
    this.organizationService.deleteInvoiceFile(invoiceFileGuid).subscribe({
      next: (response) => {
        console.log(response);
        this.pageChanged(this.currentPage, true);
      },
      error: (err) => {
        alert("Erro ao desistir da disputa.");
        console.error(err);
      },
    });
  }

  private async uploadInvoice(invoices: any) {
    const { currentManagingEntity } = this.organizationService;
    const form = new FormData();
    // form.append('sent_by', currentManagingEntity?.id ?? '');
    form.append('eco_duty', this.ecoDutyService.currentEcoDuty?.id ?? '');
    form.append('getherer', currentManagingEntity?.getherer ?? '');

    let inputInvoiceList: Array<any> = [];

    for (let invoice of invoices) {
      const file: any = await this.readFile(invoice);
      const CFOP = this.getCFOP(file);

      if (CFOP) {
        if (this.isOutput(CFOP)) {
          form.append('invoices[]', invoice);
          continue;
        }

        if (this.isInput(CFOP)) {
          inputInvoiceList.push(invoice);
          continue;
        }
      }

      // TODO: Create modal to content invoice invalid
      console.log('invoice invalid: ', invoice.name)
    }

    if (inputInvoiceList.length > 0) {
      this.openBlockedNotesModal(inputInvoiceList);
    }

    if (form.getAll('invoices[]').length > 0) {
      this.organizationService.uploadInvoice(form).subscribe({
        next: (response) => this.getInvoices(),
        error: (error) => console.log(error),
      });
    } else {
      alert("Não tem nota válida para ser enviada.");
    }
  }

  private isInput(value: number): boolean {
    return ['1', '2'].includes(value.toString());
  }

  private isOutput(value: number): boolean {
    return ['5', '6'].includes(value.toString());
  }

  private readFile(invoice: any) {
    return new Promise((resolve) => {
      let fileReader = new FileReader();
      fileReader.onload = (e) => {
        let parser = new xml2js.Parser({
          trim: true,
          explicitArray: true
        });
        parser.parseString(fileReader.result as any, (err, result) => resolve(result));
      }
      fileReader.readAsText(invoice);
    });
  }

  private getCFOP(file: any) {
    const nfe = file?.nfeProc?.NFe[0] || file?.NFe;
    const resultCfop = nfe?.infNFe[0]?.det[0]?.prod[0]?.CFOP;
    return resultCfop?.length && resultCfop[0] ? resultCfop[0][0] : false;
  }
}
