import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './components/home/home.component';
import { LoginPageComponent } from './components/login-page/login-page.component';
import { ManagingEntityPageComponent } from './components/managing-entity-page/managing-entity-page.component';
import { SignupPageComponent } from './components/signup-page/signup-page.component';
import { AuthGuard } from './guards/auth/auth.guard';
import { GuestGuard } from './guards/guest/guest.guard';
import { SystemsComponent } from './components/systems/systems.component';
import { AnnualReportPageComponent } from './components/annual-report-page/annual-report-page.component';
import { RecoveryPasswordComponent } from './components/recovery-password/recovery-password.component';
import { ResetPasswordComponent } from './components/reset-password/reset-password.component';
import { InvoicesPageComponent } from './components/invoices-page/invoices-page.component';

const routes: Routes = [
  { path: 'dashboard', component: HomeComponent, canActivate: [AuthGuard] },
  { path: 'login', component: LoginPageComponent, canActivate: [GuestGuard] },
  { path: 'signup', component: SignupPageComponent, canActivate: [GuestGuard] },
  {
    path: 'recovery-password',
    component: RecoveryPasswordComponent,
    canActivate: [GuestGuard],
  },
  {
    path: 'reset-password',
    component: ResetPasswordComponent,
    canActivate: [GuestGuard],
  },
  {
    path: 'annual-report',
    component: AnnualReportPageComponent,
    canActivate: [AuthGuard],
  },
  {
    path: 'invoices',
    component: InvoicesPageComponent,
    canActivate: [AuthGuard],
  },
  {
    path: 'organization-registration-data',
    component: ManagingEntityPageComponent,
    canActivate: [AuthGuard],
  },
  {
    path: 'systems',
    component: SystemsComponent,
    loadChildren: () =>
      import('./components/systems/systems.module').then(
        (mod) => mod.SystemsModule
      ),
    canActivate: [AuthGuard],
  },
  { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { scrollPositionRestoration: 'enabled' }),
  ],
  exports: [RouterModule],
})
export class AppRoutingModule {}
