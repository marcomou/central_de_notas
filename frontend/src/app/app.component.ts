import { Component } from '@angular/core';
import { NavigationEnd, ResolveEnd, Router } from '@angular/router';
import { environment } from 'src/environments/environment';
import { User } from './models/user';
import { Organization } from './models/organization';
import { AuthService } from './services/auth.service';
import { OrganizationService } from './services/organization.service';
import { EcoDutyService } from './services/eco-duty.service';
import { EcoDuty } from './models/eco-duty';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
})
export class AppComponent {
  private readonly freePathList = [
    '/systems/reverse-systems-list',
    '/organization-registration-data',
    '/invoices'
  ];

  private user!: User;
  private links = [
    {
      name: 'Dashboard',
      icon: environment.nhe_icons.home,
      path: 'dashboard',
      toOrganizationType: 'all',
      withRegulatoryBodySelected: true,
    },
    {
      // name: 'Sistema de Logística Reversa',
      name: 'Operadores e Recicladores',
      icon: environment.nhe_icons.system_lr,
      path: 'systems',
      toOrganizationType: 'all',
      withRegulatoryBodySelected: true,
    },
    // {
    //   name: 'Relatórios',
    //   icon: environment.nhe_icons.report,
    //   path: 'annual-report',
    //   toOrganizationType: 'all',
    //   withRegulatoryBodySelected: false,
    // },
    {
      name: 'Central de Notas Fiscais',
      icon: environment.nhe_icons.files,
      path: 'invoices',
      toOrganizationType: 'all',
      withRegulatoryBodySelected: false,
    },
  ];

  public readonly NHE_ICONS = environment.nhe_icons;

  public layoutDefault: boolean = false;
  public optionsBoxIsVisible: boolean = false;

  public organizations!: Organization[];
  public regulatoryBody?: Organization;
  public managingOrganization?: Organization;
  public ecoDuty?: EcoDuty;

  public dropdownOrgs!: { name: string; value: string }[];
  public dropdownEcoDuties: { name: string | number; value: string }[] = [];

  constructor(
    private router: Router,
    private authService: AuthService,
    private organizationService: OrganizationService,
    private ecoDutyService: EcoDutyService
  ) {
    const reverseSystemListPath = '/systems/reverse-systems-list';

    this.router.events.subscribe((res) => {
      if (res instanceof NavigationEnd || res instanceof ResolveEnd) {
        this.defineLayout();

        if (this.authService.isAuthenticated) {
          this.user = authService.currentUser;

          this.organizations = this.organizationService.organizations;
          this.dropdownOrgs = this.organizations.map((org) => ({
            name: org.front_name,
            value: org.id,
          }));

          EcoDutyService.currentEcoDutyEvent.subscribe((ecoDuty) => {
            this.ecoDuty = ecoDuty;
          });

          OrganizationService.currentOrganizationEvent.subscribe(
            (managingEntity) => {
              this.managingOrganization = managingEntity;
              this.setDropdownEcoDuties(managingEntity?.ecoDuties || []);
            }
          );

          this.prepareRegulatoryBody();
          this.prepareManagingEntity();

          if (!this.regulatoryBody && !this.managingOrganization) {
            return;
          }

          this.ecoDuty = this.ecoDutyService.currentEcoDuty;
          this.loadEcoDuties();

          if (!this.ecoDuty && !this.isInAFreePath(res.url)) {
            if (this.regulatoryBody) {
              if (!res.url.includes('dashboard')) {
                this.router.navigate([reverseSystemListPath]);
              }
            } else {
              this.router.navigate([reverseSystemListPath]);
            }
          }
        }
      }
    });
  }

  get showNavbar() {
    return (
      this.regulatoryBody ||
      (this.managingOrganization && this.hasEcoDutySelected)
    );
  }

  get hasEcoDutySelected() {
    // return this.ecoDuty !== undefined;
    return true;
  }

  get navbarLinks() {
    return this.links.filter((link) => {
      const isRegulatoryBody =
        !!this.regulatoryBody && link.toOrganizationType === 'regulatory-body';
      const isManagingEntity =
        !this.regulatoryBody && link.toOrganizationType === 'managing-entity';

      return (
        link.toOrganizationType === 'all' ||
        isRegulatoryBody ||
        isManagingEntity
      );
    });
  }

  get username(): string {
    let nameArr = this.user.name.split(' ');
    let firstName = nameArr[0];
    let lastName = nameArr[nameArr.length - 1];

    if (nameArr.length >= 3) {
      let halfName = nameArr[1].length === 2 ? nameArr[2][0] : nameArr[1][0];

      return `${firstName} ${halfName}. ${lastName}`;
    }

    if (nameArr.length == 2) {
      return `${firstName} ${lastName}`;
    }

    return firstName;
  }

  get initialsName(): string {
    let nameArr = this.user.name.split(' ');

    if (nameArr.length > 1) {
      let firstNameWord = nameArr[0][0];
      let lastNameWord = nameArr[nameArr.length - 1][0];

      return (firstNameWord + lastNameWord).toUpperCase();
    }

    return (nameArr[0][0] + nameArr[0][1]).toUpperCase();
  }

  public showLink(withRegulatoryBodySelected: boolean) {
    return (
      (withRegulatoryBodySelected && this.regulatoryBody) ||
      this.hasEcoDutySelected
    );
  }

  public changeVisibilityFromOptionsBox(value: boolean): void {
    this.optionsBoxIsVisible = value;
  }

  public signOut(): void {
    this.authService.logout().subscribe({
      next: () => {
        localStorage.clear();
        window.location.reload();
      },
      error: (error) => {
        localStorage.clear();
        window.location.reload();
      },
    });
  }

  public changeOrganization(id: string): void {
    let organization = this.organizations.find(
      (organization) => organization.id === id
    );

    if (organization) {
      this.organizationService.clearRegulatoryBody();
      this.organizationService.clearManagingEntity();

      this.organizationService.currentOrganization = organization;

      window.location.reload();
    }
  }

  public changeEcoDuty(ecoDutyId: string): void {
    if (!this.managingOrganization) {
      return;
    }

    let ecoDuty = this.managingOrganization.eco_duties?.find(
      ({ id }) => id === ecoDutyId
    );

    if (ecoDuty) {
      this.ecoDutyService.currentEcoDuty = ecoDuty;
    }
  }

  private isInAFreePath(url: string): boolean {
    for (const path of this.freePathList) {
      if (url.includes(path)) {
        return true;
      }
    }

    return false;
  }

  private prepareRegulatoryBody(): void {
    this.regulatoryBody = this.organizationService.currentRegulatoryBody;
  }

  private prepareManagingEntity(): void {
    this.managingOrganization = this.organizationService.currentManagingEntity;
  }

  private loadEcoDuties(): void {
    if (!this.managingOrganization) {
      return;
    }

    this.setDropdownEcoDuties(this.managingOrganization.eco_duties || []);

    let currentEcoDuty = this.ecoDutyService.currentEcoDuty;

    if (currentEcoDuty) {
      this.ecoDuty = currentEcoDuty;
    }
  }

  private defineLayout(): void {
    const ROUTES = [
      '/login',
      '/signup',
      '/recovery-password',
      '/reset-password',
    ];

    const URL_CURRENT = this.getPathCurrent();

    this.layoutDefault = ROUTES.findIndex((r) => r === URL_CURRENT) == -1;
  }

  private getPathCurrent(): string {
    return window.location.pathname;
  }

  private setDropdownEcoDuties(ecoDuties: EcoDuty[]) {
    this.dropdownEcoDuties = ecoDuties.map((eco) => {
      const acronym = eco.eco_ruleset.eco_system?.location?.acronym;
      const dutyYear = eco.eco_ruleset.duty_year;
      const managingCode = eco.managing_code;

      return {
        name: `${acronym}|${dutyYear}|${managingCode}`,
        value: eco.id,
      };
    });
  }
}
