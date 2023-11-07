import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { CollapseModule } from 'ngx-bootstrap/collapse';
import { ModalModule } from 'ngx-bootstrap/modal';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from './interceptors/auth/auth.interceptor';
import { PaginationModule } from 'ngx-bootstrap/pagination';
import { ComponentsModule } from './components/components.module';
import { SystemsModule } from './components/systems/systems.module';
import { CommonModule } from '@angular/common';
import { NgHttpLoaderModule } from 'ng-http-loader';
import { ChartComponent } from './components/chart/chart.component';

@NgModule({
  declarations: [AppComponent],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    CommonModule,
    CollapseModule.forRoot(),
    ModalModule.forRoot(),
    PaginationModule.forRoot(),
    HttpClientModule,
    ComponentsModule,
    SystemsModule,
    NgHttpLoaderModule.forRoot(),
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true },
  ],
  bootstrap: [AppComponent],
})
export class AppModule {}
