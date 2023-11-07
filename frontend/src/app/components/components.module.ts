import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HomeComponent } from './home/home.component';
import { LoginPageComponent } from './login-page/login-page.component';
import { NheInputComponent } from './nhe-input/nhe-input.component';
import { SignupPageComponent } from './signup-page/signup-page.component';
import { BoxComponent } from './box/box.component';
import { InputRefDirective } from '../directives/input-ref.directive';
import { SidebarComponent } from './sidebar/sidebar.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { CollapseModule } from 'ngx-bootstrap/collapse';
import { PaginationModule } from 'ngx-bootstrap/pagination';
import { AccordionModule } from 'ngx-bootstrap/accordion';
import { BsDropdownModule } from 'ngx-bootstrap/dropdown';

import { GenericTableComponent } from './generic-table/generic-table.component';
import { RouterModule } from '@angular/router';
import { AlertComponent } from './alert/alert.component';
import { CpfPipe } from '../helpers/pipes/cpf.pipe';
import { CnpjPipe } from '../helpers/pipes/cnpj.pipe';
import { ManagingEntityPageComponent } from './managing-entity-page/managing-entity-page.component';
import { NheSelectComponent } from './nhe-select/nhe-select.component';
import { FileUploadModelComponent } from './file-upload-model/file-upload-model.component';
import { NheFileUploadComponent } from './nhe-file-upload/nhe-file-upload.component';
import { NheTabComponent } from './nhe-tab/nhe-tab.component';
import { AnnualReportPageComponent } from './annual-report-page/annual-report-page.component';
import { NhePaginationComponent } from './nhe-pagination/nhe-pagination.component';
import { NheEcoDutyStatusComponent } from './nhe-eco-duty-status/nhe-eco-duty-status.component';
import { NheTitleComponent } from './nhe-title/nhe-title.component';
import { NheBarChartComponent } from './nhe-bar-chart/nhe-bar-chart.component';
import { NgxEchartsModule } from 'ngx-echarts';
import { NhePieChartComponent } from './nhe-pie-chart/nhe-pie-chart.component';
import { ChartComponent } from './chart/chart.component';
import { NhePieBarChartComponent } from './nhe-pie-bar-chart/nhe-pie-bar-chart.component';
import { RecoveryPasswordComponent } from './recovery-password/recovery-password.component';
import { ResetPasswordComponent } from './reset-password/reset-password.component';
import { NgHttpLoaderModule } from 'ng-http-loader';
import { InvoicesPageComponent } from './invoices-page/invoices-page.component';
import { DeleteConfirmationModalComponent } from './delete-confirmation-modal/delete-confirmation-modal.component';
import { ErrorModalComponent } from './error-modal/error-modal.component';
import { InvoiceDetailPageComponent } from './invoice-detail-page/invoice-detail-page.component';
import { PopoverModule } from 'ngx-bootstrap/popover';

@NgModule({
  declarations: [
    AlertComponent,
    HomeComponent,
    LoginPageComponent,
    RecoveryPasswordComponent,
    NheInputComponent,
    SignupPageComponent,
    ManagingEntityPageComponent,
    BoxComponent,
    InputRefDirective,
    SidebarComponent,
    GenericTableComponent,
    CpfPipe,
    CnpjPipe,
    NheSelectComponent,
    FileUploadModelComponent,
    NheFileUploadComponent,
    NheTabComponent,
    AnnualReportPageComponent,
    NhePaginationComponent,
    NheEcoDutyStatusComponent,
    NheTitleComponent,
    NheBarChartComponent,
    NhePieChartComponent,
    ChartComponent,
    NhePieBarChartComponent,
    ResetPasswordComponent,
    InvoicesPageComponent,
    DeleteConfirmationModalComponent,
    ErrorModalComponent,
    InvoiceDetailPageComponent,
  ],
  exports: [
    AlertComponent,
    HomeComponent,
    ManagingEntityPageComponent,
    LoginPageComponent,
    RecoveryPasswordComponent,
    ResetPasswordComponent,
    NheInputComponent,
    InputRefDirective,
    SignupPageComponent,
    BoxComponent,
    SidebarComponent,
    GenericTableComponent,
    CpfPipe,
    CnpjPipe,
    NheSelectComponent,
    FileUploadModelComponent,
    NheFileUploadComponent,
    NheTabComponent,
    AnnualReportPageComponent,
    NhePaginationComponent,
    NheEcoDutyStatusComponent,
    NheTitleComponent,
    ChartComponent,
  ],

  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    PopoverModule.forRoot(),
    CollapseModule.forRoot(),
    PaginationModule.forRoot(),
    AccordionModule.forRoot(),
    BsDropdownModule.forRoot(),
    NgxEchartsModule.forRoot({
      echarts: () => import('echarts'),
    }),
    NgHttpLoaderModule.forRoot(),
    RouterModule,
  ],
})
export class ComponentsModule {}
