import { Component, Input, OnInit } from '@angular/core';
import { BsModalService } from 'ngx-bootstrap/modal';
import { AlertComponent } from 'src/app/components/alert/alert.component';
import { FileUploadModelComponent } from 'src/app/components/file-upload-model/file-upload-model.component';
import { AsyncStatus } from 'src/app/models/async-status';
import { EcoMembership } from 'src/app/models/eco-membership';
import { InvoiceFile } from 'src/app/models/invoice/invoice-file';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { EcoMembershipService } from 'src/app/services/eco-membership.service';
import { OrganizationService } from 'src/app/services/organization.service';
import { RuleService } from 'src/app/services/rule.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-invoices-logistic-operator-subpage',
  templateUrl: './invoices-logistic-operator-subpage.component.html',
  styleUrls: ['./invoices-logistic-operator-subpage.component.scss'],
})
export class InvoicesLogisticOperatorSubpageComponent implements OnInit {
  private status = AsyncStatus.idle;

  public invoiceFiles: InvoiceFile[] = [];
  // public invoicesResume = [
  //   {
  //     code: 'paper',
  //     material: {
  //       name: 'Papel',
  //       icon: environment.nhe_icons.paper,
  //     },
  //     inputRead: 0,
  //     inputApproved: 0,
  //     outputRead: 0,
  //     outputApproved: 0,
  //   },
  //   {
  //     code: 'plastic',
  //     material: {
  //       name: 'Plástico',
  //       icon: environment.nhe_icons.plastic,
  //     },
  //     inputRead: 0,
  //     inputApproved: 0,
  //     outputRead: 0,
  //     outputApproved: 0,
  //   },
  //   {
  //     code: 'glass',
  //     material: {
  //       name: 'Vidro',
  //       icon: environment.nhe_icons.glass,
  //     },
  //     inputRead: 0,
  //     inputApproved: 0,
  //     outputRead: 0,
  //     outputApproved: 0,
  //   },
  //   {
  //     code: 'metal',
  //     material: {
  //       name: 'Metal',
  //       icon: environment.nhe_icons.metal,
  //     },
  //     inputRead: 0,
  //     inputApproved: 0,
  //     outputRead: 0,
  //     outputApproved: 0,
  //   },
  // ];

  public invoicesResume: {[k: string]: { icon: string, mass: number }} = {
    "Papel": {
      icon: environment.nhe_icons.paper,
      mass: 0,
    },
    "Plástico": {
      icon: environment.nhe_icons.plastic,
      mass: 0,
    },
    "Vidro": {
      icon: environment.nhe_icons.glass,
      mass: 0,
    },
    "Metal": {
      icon: environment.nhe_icons.metal,
      mass: 0,
    },
  };

  @Input()
  public ecoMembership?: EcoMembership;

  public currentPage = 1;
  public skip: number = 0;
  public top: number = 10;
  public total: number = 0;

  constructor(
    private ecoDutyService: EcoDutyService,
    private ecoMembershipService: EcoMembershipService,
    private modalService: BsModalService,
    private organizationService: OrganizationService,
    private ruleService: RuleService
  ) {}

  ngOnInit(): void {
    if (this.ecoMembership) {
      this.getInvoices();
      this.getOperationMasses();
    }
  }

  get canUpdateLogisticOperator() {
    return this.ruleService.systemRules.canUpdateLogisticOperator;
  }

  public getInvoices(page?: number): void {
    if (!this.ecoMembership || this.status.isProcessing) {
      return;
    }

    if (page) {
      if (this.currentPage === page) {
        return;
      }
      this.currentPage = page;
    }

    this.status = AsyncStatus.loading;

    const params = {
      skip: (this.currentPage - 1) * this.top,
      // eco_duty: this.ecoDutyService.currentEcoDuty?.id ?? '',
      // getherer: this.organizationService.currentManagingEntity?.getherer ?? '',
      // search: this.ecoMembershipService.currentEcoMembership?.member_organization?.federal_registration ?? ''
    };

   /*  this.ecoMembershipService.getInvoicesList(params).subscribe({
      next: (response) => {
        console.log(this.invoiceFiles);
        this.invoiceFiles = response.data;
        this.skip = response.skip;
        this.top = response.top;
        this.total = response.total;
      },
      error: (error) => console.log(error),
    });  */

    this.ecoMembershipService
      .getInvoices(this.ecoMembership, params)
      .subscribe({
        next: (response) => {
          this.invoiceFiles = response.data;
          this.skip = response.skip;
          this.top = response.top;
          this.total = response.total;

          this.status = AsyncStatus.idle;
        },
        error: (error) => {
          this.status = AsyncStatus.error(error.status, error.message);
          alert('Erro na busca de notas fiscais');
        },
      }); 
  }

  public isValid(invoiceFile: InvoiceFile): boolean {
    const isRegistered = invoiceFile.status === 0;
    const isAccepted = invoiceFile.status === 3;
    const invoice = invoiceFile?.invoice;

    if (isAccepted && invoice) {
      const status = invoice.status;

      switch (status) {
        case 'valid':
        case 'validated':
        case 'registered':
        case 'tracked':
        case 'considered':
          return true;
      }

      return false;
    }

    return isRegistered || isAccepted;
  }

  public getMaterials(invoiceFile: InvoiceFile): string | undefined {
    return invoiceFile?.invoice?.invoice_items
      ?.map((item) => item.ncm_material)
      .join(', ');
  }

  public getStatus(invoiceFile: InvoiceFile): string {
    return invoiceFile.getInvoiceStatus() || this.invoiceFiles[0].getStatus();
  }

  public openFileUploadModal() {
    this.modalService.show(FileUploadModelComponent, {
      animated: true,
      class: 'modal-lg',
      initialState: {
        acceptTypes: [],
        allowMultiple: true,
        onSelected: (files) => this.uploadInvoice(files),
      },
    });
  }

  private getOperationMasses(): void {
    if (this.ecoMembership?.id) {
      // this.ecoDutyService.getOperationMasses(this.ecoMembership.id).subscribe((operationMasses: any[]) => {
      //   // code: "glass"
      //   // id: "3144b8a4-537f-4ed0-a943-da91bca736df"
      //   // name: "Vidro"
      //   // read_incoming_weight: "8371"
      //   // read_outgoing_weight: "16274"
      //   // validated_incoming_weight: "27195"
      //   // validated_outgoing_weight: "12623"

      //   this.invoicesResume.map(invoiceResume => {
      //     const operationMass = operationMasses.find(om => invoiceResume.code === om.code);

      //     invoiceResume.inputRead = 0;
      //     invoiceResume.inputApproved = 0;
      //     invoiceResume.outputRead = 0;
      //     invoiceResume.outputApproved = 0;

      //     if (operationMass) {
      //       invoiceResume.inputRead = operationMass.read_incoming_weight;
      //       invoiceResume.inputApproved = operationMass.validated_incoming_weight;
      //       invoiceResume.outputRead = operationMass.read_outgoing_weight;
      //       invoiceResume.outputApproved = operationMass.validated_outgoing_weight;
      //     }
      //   });
      // });

      const params = {
        operator: this.ecoMembership.member_organization?.federal_registration,
      }

      this.ecoDutyService.getOperationMasses(params).subscribe((outgoingMasses: any) => {
        Object.keys(outgoingMasses).forEach((material) => {
          this.invoicesResume[material].mass = outgoingMasses[material];
        });
      });
    }
  }

  private uploadInvoice(invoices: FileList) {
    const id = this.organizationService.currentOrganization.id;
    const form = new FormData();

    for (let i = 0; i < invoices.length; i++) {
      form.append('invoices[]', invoices.item(i) as any);
    }

    form.append(
      'sent_by',
      this.ecoMembershipService.currentEcoMembership?.id ?? ''
    );
    form.append(
      'eco_duty',
      this.ecoDutyService.currentEcoDuty?.id ?? ''
    );
    form.append(
      'getherer',
      this.organizationService.currentManagingEntity?.getherer ?? ''
    );
    this.organizationService.uploadInvoice(form).subscribe({
      next: (response) => this.getInvoices(),
      error: (error) => console.log(error),
    });
  }
}
