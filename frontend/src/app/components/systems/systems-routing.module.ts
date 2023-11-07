import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { EcoDutyResolver } from 'src/app/resolvers/eco-duty.resolver';
import { EcoMembershipResolver } from 'src/app/resolvers/eco-membership.resolver';
import { AdheringCompaniesListComponent } from './adhering-companiy-page/adhering-companies-list/adhering-companies-list.component';
import { AdheringCompaniyPageComponent } from './adhering-companiy-page/adhering-companiy-page.component';
import { MassDeclarationAdheringCompaniySubpageComponent } from './adhering-companiy-page/mass-declaration-adhering-companiy-subpage/mass-declaration-adhering-companiy-subpage.component';
import { GeneralDataPageComponent } from './general-data-page/general-data-page.component';
import { LogisticOperatorListComponent } from './logistic-operator-page/logistic-operator-list/logistic-operator-list.component';
import { LogisticOperatorPageComponent } from './logistic-operator-page/logistic-operator-page.component';
import { RecyclerPageComponent } from './recycler-page/recycler-page.component';
import { RecyclersListComponent } from './recycler-page/recyclers-list/recyclers-list.component';
import { RepresentativeEntityListComponent } from './representative-entity-page/representative-entity-list/representative-entity-list.component';
import { RepresentativeEntityPageComponent } from './representative-entity-page/representative-entity-page.component';
import { ReverseSystemListPageComponent } from './reverse-system-list-page/reverse-system-list-page.component';
import { DataReverseSystemSubpageComponent } from './reverse-system-list-page/data-reverse-system-subpage/data-reverse-system-subpage.component';


const routes: Routes = [
  // { path: '', redirectTo: 'general-data', pathMatch: 'full' },
  { path: '', redirectTo: 'logistic-operators', pathMatch: 'full' },
  { path: 'general-data', component: GeneralDataPageComponent, resolve: { ecoDuty: EcoDutyResolver } },

  {
    path: 'adhering-companies',
    children: [
      { path: 'list', component: AdheringCompaniesListComponent },
      { path: 'form', component: AdheringCompaniyPageComponent },
      { path: 'mass-declaration', component: MassDeclarationAdheringCompaniySubpageComponent },
      { path: '', redirectTo: 'list', pathMatch: 'full' },
    ],
  },
  {
    path: 'representative-entity',
    children: [
      {
        path: 'list',
        component: RepresentativeEntityListComponent,
      },
      {
        path: 'form',
        component: RepresentativeEntityPageComponent,
      },
      { path: '', redirectTo: 'list', pathMatch: 'full' },
    ],
  },
  {
    path: 'logistic-operators',
    children: [
      { path: 'list', component: LogisticOperatorListComponent },
      { path: 'form', component: LogisticOperatorPageComponent },
      { path: ':id/form', component: LogisticOperatorPageComponent, resolve: { ecoMembership: EcoMembershipResolver } },

      { path: '', redirectTo: 'list', pathMatch: 'full' },
    ],
  },

  {
    path: 'recyclers',
    children: [
      { path: 'list', component: RecyclersListComponent },
      { path: 'form', component: RecyclerPageComponent },
      { path: '', redirectTo: 'list', pathMatch: 'full' },
    ],
  },
  {
    path: 'reverse-systems-list',
    children: [
      { path: 'list', component: ReverseSystemListPageComponent },
      { path: 'form', component: DataReverseSystemSubpageComponent },
      { path: '', redirectTo: 'list', pathMatch: 'full' },
    ],
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class SystemsRoutingModule {}
