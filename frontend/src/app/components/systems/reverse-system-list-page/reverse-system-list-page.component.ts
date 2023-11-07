import { Component, OnInit, TemplateRef } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';
import { PageChangedEvent } from 'ngx-bootstrap/pagination';
import { AsyncStatus } from 'src/app/models/async-status';
import { EcoDuty } from 'src/app/models/eco-duty';
import { Organization } from 'src/app/models/organization';
import { RequestAPI } from 'src/app/models/request';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { EcoRulesetService } from 'src/app/services/eco-ruleset.service';
import { OrganizationService } from 'src/app/services/organization.service';
import { DeleteConfirmationModalComponent } from '../../delete-confirmation-modal/delete-confirmation-modal.component';
import { SystemsComponent } from '../systems.component';

@Component({
  selector: 'app-reverse-system-list-page',
  templateUrl: './reverse-system-list-page.component.html',
  styleUrls: ['./reverse-system-list-page.component.scss'],
})
export class ReverseSystemListPageComponent implements OnInit {
  private status = AsyncStatus.idle;
  private currentPage: number = 1;
  private currentOrganization: Organization;
  private setTimeoutId?: any;

  public query: string = '';
  public ecoDuties!: EcoDuty[] | undefined;
  public ecoRulesets: any;
  public metadata!: RequestAPI<EcoDuty[]>;
  public modalRef?: BsModalRef;
  public ecoDutyForm: FormGroup;
  public ecoRuleseta!: string;
  public currentEcoduty?: EcoDuty;

  constructor(
    private ecoDutyService: EcoDutyService,
    private modalService: BsModalService,
    private ecoRulesetsService: EcoRulesetService,
    private formBuilder: FormBuilder,
    private organizationService: OrganizationService,
    private router: Router
  ) {
    const { currentRegulatoryBody, currentManagingEntity } =
      this.organizationService;
    this.currentOrganization =
      currentRegulatoryBody || (currentManagingEntity as Organization);

    this.ecoDutyForm = this.formBuilder.group({
      eco_ruleset_id: ['', Validators.required],
      managing_organization_id: [
        this.organizationService.currentOrganization.id,
        Validators.required,
      ],
      managing_code: [`SIS${Math.random()}`],
      status: ['replaced'],
    });
    if(!currentRegulatoryBody){
        this.router.navigate(['/systems/logistic-operators']);
    }
  }

  ngOnInit(): void {
    this.getEcoDuties();
    this.getEcoRulesets();

    this.selectFirstEcoDuty();

    this.currentEcoduty = this.ecoDutyService.currentEcoDuty;
    EcoDutyService.currentEcoDutyEvent.subscribe(
      (ecoDuty) => (this.currentEcoduty = ecoDuty)
    );
  }

  get isProgressing() {
    return this.status.isProcessing;
  }

  get sidebarHasMinimized(): boolean {
    return SystemsComponent.sidebarHasMinimized;
  }

  get regulatoryBodyHasSelected() {
    return this.currentOrganization.isRegulatoryBody;
  }

  get title(): string {
    return 'Sistemas de Logística Reversa';
  }

  public search(query: string): void {
    if (this.setTimeoutId) {
      clearTimeout(this.setTimeoutId);
      delete this.setTimeoutId;
    }

    this.setTimeoutId = setTimeout(() => {
      this.getEcoDuties({ q: query });
    }, 2500);
  }

  public setEcoDuty(ecoDuty: EcoDuty): void {
    let federalRegistration: false | string = false;

    if (this.currentOrganization.isRegulatoryBody) {
      const managingOrganization = new Organization(
        ecoDuty.managing_organization
      );
      this.organizationService.currentManagingEntity = managingOrganization;

      federalRegistration = managingOrganization?.federalRegistration;
    }

    this.ecoDutyService.currentEcoDuty = ecoDuty;

    if (federalRegistration) {
      this.getEcoDuties({ q: federalRegistration }, true);
    }
  }

  public isCurrentEcoDuty(ecoDuty: EcoDuty): boolean {
    return ecoDuty.id === this.currentEcoduty?.id;
  }

  public pageChanged(event: PageChangedEvent) {
    let options: { [k: string]: any } = {};

    if (event?.page) {
      options['page'] = event?.page;
    }

    this.getEcoDuties(options);
  }

  public openModal = (template: TemplateRef<any>): void => {
    this.modalRef = this.modalService.show(template, {
      class: 'modal-lg',
      animated: true,
    });
  };

  public closeModal = () => this.modalRef?.hide();

  public submit = (): void => {
    this.ecoDutyService.storeEcoDuty(this.ecoDutyForm.value).subscribe({
      next: (response: RequestAPI<EcoDuty>) => {
        this.closeModal();
        this.router.navigate(['systems/general-data'], {
          state: { ecoDuty: response.data },
        });
      },
      error: (e) => console.log(e),
    });
  };

  public delete = (ecoDuty: EcoDuty): void => {
    this.modalService.show(DeleteConfirmationModalComponent, {
      initialState: {
        confirmed: (response) => {
          if (response) {
            this.ecoDutyService.deleteEcoDuty(ecoDuty.id).subscribe({
              next: () => this.getEcoDuties(),
              error: (error) => console.log(error),
            });
          }
        },
      },
    });
  };

  private selectFirstEcoDuty(): void {
    if (this.ecoDuties) {
      this.ecoDutyService.currentEcoDuty = this.ecoDuties[0];
      // this.router.navigate(['/systems/logistic-operators']);
    }
  }

  private getEcoRulesets = (): void => {
    this.ecoRulesetsService.getEcoRulesets().subscribe({
      next: (response) => (this.ecoRulesets = response.data),
      error: (errpr) => console.log(errpr),
    });
  };

  private getEcoDuties = (
    options?: { [k: string]: any },
    updateEcoDutyListInOrganization = false
  ): void => {
    const page = options && options['page'];
    if (this.isProgressing || page === this.currentPage) {
      return;
    }
    this.status = AsyncStatus.loading;

    this.ecoDutyService.getOrganizationEcoDuties(options).subscribe({
      next: (response: RequestAPI<EcoDuty[]>): void => {
        if (!updateEcoDutyListInOrganization) {
          this.ecoDuties = response.data;
          this.metadata = response;
          delete this.metadata.data;

          this.currentPage = response.meta.current_page;
        }

        const { currentManagingEntity } = this.organizationService;

        if (updateEcoDutyListInOrganization && currentManagingEntity) {
          currentManagingEntity.ecoDuties = response.data;
          this.organizationService.currentManagingEntity =
            currentManagingEntity;
        }

        this.status = AsyncStatus.success;
      },
      error: (error) => {
        console.log(error);
        this.status = AsyncStatus.error(error.code, error.message);
      },
    });
  };
}
