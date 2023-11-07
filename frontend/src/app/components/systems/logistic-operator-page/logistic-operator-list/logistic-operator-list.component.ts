import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { BsModalService } from 'ngx-bootstrap/modal';
import { DeleteConfirmationModalComponent } from 'src/app/components/delete-confirmation-modal/delete-confirmation-modal.component';
import { HeaderTableItem } from 'src/app/components/generic-table/generic-table.component';
import { EcoMembership } from 'src/app/models/eco-membership';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { EcoMembershipService } from 'src/app/services/eco-membership.service';

@Component({
  selector: 'app-logistic-operator-list',
  templateUrl: './logistic-operator-list.component.html',
  styleUrls: ['./logistic-operator-list.component.scss'],
})
export class LogisticOperatorListComponent implements OnInit {
  public tableHeaders: HeaderTableItem[];
  public representativeEntitiesList!: EcoMembership[] | undefined;

  constructor(
    private ecoMembershipService: EcoMembershipService,
    private ecoDutyService: EcoDutyService,
    private router: Router,
    private modalService: BsModalService
  ) {
    this.tableHeaders = [
      { name: 'Razão Social' },
      { name: 'CNPJ', class: 'col-lg-3', sortable: true },
      { name: 'Endereço' },
      { name: 'Município UF ' },
      { name: 'Ações' },
    ];
  }

  ngOnInit(): void {
    this.getRepresentativeEntities();
  }

  getRepresentativeEntities = () => {
    const ecoDuty = this.ecoDutyService.currentEcoDuty;
    if (!ecoDuty) {
      return;
    }

    this.ecoMembershipService
      .getEcoMemberShipByEcoDuty(ecoDuty.id, 'member_role=operator')
      .subscribe({
        next: (response) => (this.representativeEntitiesList = response.data),
        error: (e) => console.log(e),
      });
  };

  edit = (membership: EcoMembership): void => {
    this.ecoMembershipService.currentEcoMembership = membership;
    this.router.navigate([`systems/logistic-operators/${membership.id}/form`], {
      state: { ecoMembership: membership },
    });
  };

  delete = (membership: EcoMembership): void => {
    this.modalService.show(DeleteConfirmationModalComponent, {
      initialState: {
        confirmed: (response) => {
          if (response) {
            this.ecoMembershipService
              .deleteEcoMembership(membership)
              .subscribe({
                next: () => {
                  this.getRepresentativeEntities();
                },
                error: (error) => {
                  console.log(error);
                },
              });
          }
        },
      },
    });
  };
}
