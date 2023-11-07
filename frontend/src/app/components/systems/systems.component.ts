import { Component, OnInit } from '@angular/core';
import { EcoDuty } from 'src/app/models/eco-duty';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { OrganizationService } from 'src/app/services/organization.service';
import { SidebarMenuItem } from '../sidebar/sidebar.component';

@Component({
  selector: 'app-systems',
  templateUrl: './systems.component.html',
  styleUrls: ['./systems.component.scss'],
})
export class SystemsComponent implements OnInit {
  private ecoDuty?: EcoDuty;

  public static sidebarHasMinimized: boolean = false;

  public menuItems: SidebarMenuItem[] = [];
  // public title = "Sistemas de Logística Reversa";
  public title = "";

  constructor(private ecoDutyService: EcoDutyService, private organizationService: OrganizationService) {
    this.defineMenuItems();
    this.ecoDuty = this.ecoDutyService.currentEcoDuty;
    EcoDutyService.currentEcoDutyEvent.subscribe(
      (ecoDuty) => {
        this.ecoDuty = ecoDuty
        this.defineMenuItems();
      }
    );
  }

  private defineMenuItems() {
    this.menuItems = [
      // {
      //   name: 'Dados Gerais',
      //   icon: 'description',
      //   routerLink: ['general-data'],
      // },
      // {
      //   name: 'Entidades Representativas',
      //   icon: 'corporate_fare',
      //   routerLink: ['representative-entity'],
      // },
      // {
      //   name: 'Empresas Aderentes',
      //   icon: 'business_center',
      //   routerLink: ['adhering-companies'],
      // },
    ];

    if(this.organizationService.currentManagingEntity){
      this.menuItems.push(
        {
          name: 'Operadores Logísticos',
          icon: 'local_shipping',
          routerLink: ['logistic-operators'],
        },
        {
          name: 'Recicladoras',
          icon: 'recycling',
          routerLink: ['recyclers'],
        }
      )
    }
    if(this.organizationService.currentRegulatoryBody){
      this.menuItems.push({
        name: 'Entidades Gestoras',
        icon: 'recycling',
        routerLink: ['reverse-systems-list'],
      })
    }
  }

  get hasEcoDutySelected() {
    // return !!this.ecoDuty;
    return true;
  }

  ngOnInit(): void {}

  public sidebarChanged(event: { hasMinimized: boolean }): void {
    SystemsComponent.sidebarHasMinimized = event.hasMinimized;
  }
}
