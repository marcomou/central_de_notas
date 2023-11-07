import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SystemsRoutingModule } from './systems-routing.module';
import { ReverseSystemListPageComponent } from './reverse-system-list-page/reverse-system-list-page.component';
import { ComponentsModule } from '../components.module';
import { SystemsComponent } from './systems.component';
import { PaginationModule } from 'ngx-bootstrap/pagination';
import { GeneralDataPageComponent } from './general-data-page/general-data-page.component';
import { RepresentativeEntityPageComponent } from './representative-entity-page/representative-entity-page.component';
import { AdheringCompaniyPageComponent } from './adhering-companiy-page/adhering-companiy-page.component';
import { LogisticOperatorPageComponent } from './logistic-operator-page/logistic-operator-page.component';
import { RecyclerPageComponent } from './recycler-page/recycler-page.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DataAdheringCompaniySubpageComponent } from './adhering-companiy-page/data-adhering-companiy-subpage/data-adhering-companiy-subpage.component';
import { DocumentsAdheringCompaniySubpageComponent } from './adhering-companiy-page/documents-adhering-companiy-subpage/documents-adhering-companiy-subpage.component';
import { DataLogisticOperatorSubpageComponent } from './logistic-operator-page/data-logistic-operator-subpage/data-logistic-operator-subpage.component';
import { DocumentsLogisticOperatorSubpageComponent } from './logistic-operator-page/documents-logistic-operator-subpage/documents-logistic-operator-subpage.component';
import { HomologationLogisticOperatorSubpageComponent } from './logistic-operator-page/homologation-logistic-operator-subpage/homologation-logistic-operator-subpage.component';
import { InvoicesLogisticOperatorSubpageComponent } from './logistic-operator-page/invoices-logistic-operator-subpage/invoices-logistic-operator-subpage.component';
import { RepresentativeEntityListComponent } from './representative-entity-page/representative-entity-list/representative-entity-list.component';
import { AdheringCompaniesListComponent } from './adhering-companiy-page/adhering-companies-list/adhering-companies-list.component';
import { LogisticOperatorListComponent } from './logistic-operator-page/logistic-operator-list/logistic-operator-list.component';
import { DataRecyclerSubpageComponent } from './recycler-page/data-recycler-subpage/data-recycler-subpage.component';
import { DocumentsRecyclerSubpageComponent } from './recycler-page/documents-recycler-subpage/documents-recycler-subpage.component';
import { MassDeclarationAdheringCompaniySubpageComponent } from './adhering-companiy-page/mass-declaration-adhering-companiy-subpage/mass-declaration-adhering-companiy-subpage.component';
import { RecyclersListComponent } from './recycler-page/recyclers-list/recyclers-list.component';
import {BlockedNotesModalComponent} from '../blocked-notes-modal/blocked-notes-modal.component';
import { AlertComponent } from '../alert/alert.component';
import { DataReverseSystemSubpageComponent } from './reverse-system-list-page/data-reverse-system-subpage/data-reverse-system-subpage.component';

@NgModule({
  declarations: [
    ReverseSystemListPageComponent,
    SystemsComponent,
    GeneralDataPageComponent,
    RepresentativeEntityPageComponent,
    AdheringCompaniyPageComponent,
    LogisticOperatorPageComponent,
    RecyclerPageComponent,
    DataAdheringCompaniySubpageComponent,
    DocumentsAdheringCompaniySubpageComponent,
    DataLogisticOperatorSubpageComponent,
    DocumentsLogisticOperatorSubpageComponent,
    HomologationLogisticOperatorSubpageComponent,
    InvoicesLogisticOperatorSubpageComponent,
    RepresentativeEntityListComponent,
    AdheringCompaniesListComponent,
    LogisticOperatorListComponent,
    DataRecyclerSubpageComponent,
    DocumentsRecyclerSubpageComponent,
    MassDeclarationAdheringCompaniySubpageComponent,
    RecyclersListComponent,
    DataReverseSystemSubpageComponent,
    BlockedNotesModalComponent,
  ],
  imports: [
    ComponentsModule,
    CommonModule,
    SystemsRoutingModule,
    PaginationModule.forRoot(),
    FormsModule,
    ReactiveFormsModule,
  ],
})
export class SystemsModule {}
