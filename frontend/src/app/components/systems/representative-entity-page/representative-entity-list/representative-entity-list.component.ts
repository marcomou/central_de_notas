import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { BsModalService } from 'ngx-bootstrap/modal';
import { DeleteConfirmationModalComponent } from 'src/app/components/delete-confirmation-modal/delete-confirmation-modal.component';
import { EcoMembership } from 'src/app/models/eco-membership';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { EcoMembershipService } from 'src/app/services/eco-membership.service';

@Component({
  selector: 'app-representative-entity-list',
  templateUrl: './representative-entity-list.component.html',
  styleUrls: ['./representative-entity-list.component.scss'],
})
export class RepresentativeEntityListComponent implements OnInit {
  public representativeEntitiesList!: EcoMembership[] | undefined;

  constructor(
    private ecoMembershipService: EcoMembershipService,
    private ecoDutyService: EcoDutyService,
    private router: Router,
    private modalService: BsModalService
  ) {}

  ngOnInit(): void {
    this.getRepresentativeEntities();
  }

  getRepresentativeEntities = () => {
    const ecoDuty = this.ecoDutyService.currentEcoDuty;
    if (!ecoDuty) {
      return;
    }

    this.ecoMembershipService
      .getEcoMemberShipByEcoDuty(ecoDuty.id, `member_role=liable`)
      .subscribe({
        next: (response) => (this.representativeEntitiesList = response.data),
        error: (e) => console.log(e),
      });
  };

  edit = (membership: EcoMembership): void => {
    this.router.navigate(['systems/representative-entity/form'], {
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
