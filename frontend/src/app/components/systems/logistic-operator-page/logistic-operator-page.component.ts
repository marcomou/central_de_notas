import { Component, OnInit } from '@angular/core';
import {
  ActivatedRoute,
  ActivatedRouteSnapshot,
  Router,
} from '@angular/router';
import { EcoMembership } from 'src/app/models/eco-membership';
import { EcoMembershipService } from 'src/app/services/eco-membership.service';

@Component({
  selector: 'app-logistic-operator-page',
  templateUrl: './logistic-operator-page.component.html',
  styleUrls: ['./logistic-operator-page.component.scss'],
})
export class LogisticOperatorPageComponent implements OnInit {
  public subpages = {
    data: {
      title: 'Dados',
      enabled: true,
      status: 'done',
    },
    documents: {
      title: 'Documentos',
      enabled: false,
      status: 'pending',
    },
    homologation: {
      title: 'Homologação',
      enabled: false,
      status: 'pending',
    },
    invoices: {
      title: 'Notas Fiscais',
      enabled: false,
      status: 'pending',
    },
  };

  public ecoMembership?: EcoMembership;
  constructor(
    private ecoMembershipService: EcoMembershipService,
    private activatedRoute: ActivatedRoute, 
    private router: Router
  ) {
    // const ecoMembership = this.router.getCurrentNavigation()?.extras.state?.['ecoMembership'];
    // if (ecoMembership) {
    //   this.ecoMembership = ecoMembership;
    // }

    if (this.router.url.includes('/systems/logistic-operators/form')) {
      this.ecoMembershipService.currentEcoMembership = undefined;
    } else {
      this.ecoMembership = this.ecoMembershipService.currentEcoMembership;
    }
  }

  ngOnInit(): void {}
}
