import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { map, Observable, retry } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Contact } from '../models/contact';
import { EcoMembership } from '../models/eco-membership';
import { InvoiceFile } from '../models/invoice/invoice-file';
import { RequestAPI, RequestPaginateModel2 } from '../models/request';
import { BaseService } from './base.service';

@Injectable({
  providedIn: 'root',
})
export class EcoMembershipService extends BaseService {
  constructor(private httpClient: HttpClient) {
    super();
  }

  getEcoMembersShip = (
    param?: string
  ): Observable<RequestAPI<EcoMembership[]>> => {
    return this.httpClient.get<RequestAPI<EcoMembership[]>>(
      `${environment.apiEndpoint}/eco_memberships?${param}`
    );
  };

  getEcoMemberShip = (
    ecoMembershipId?: string
  ): Observable<RequestAPI<EcoMembership>> => {
    const path = `${environment.apiEndpoint}/eco_memberships/${ecoMembershipId}`;
    return this.httpClient.get<RequestAPI<EcoMembership>>(path).pipe(
      map((response) => {
        this.currentEcoMembership = response.data;
        return response;
      })
    );
  };

  getEcoMemberShipByEcoDuty = (
    ecoDutyId: string,
    queryString?: string
  ): Observable<RequestAPI<EcoMembership[]>> => {
    const path = `${environment.apiEndpoint}/eco_duties/${ecoDutyId}/eco_memberships?${queryString}`;
    return this.httpClient.get<RequestAPI<EcoMembership[]>>(path);
  };

  getContacts = (
    ecoMembershipId: string | undefined,
    queryString?: string
  ): Observable<RequestAPI<Contact[]>> => {
    return this.httpClient.get<RequestAPI<Contact[]>>(
      `${environment.apiEndpoint}/eco_memberships/${ecoMembershipId}/contacts?${queryString}`
    );
  };

  storeEcoMemberShip = (
    ecoMembership: EcoMembership
  ): Observable<RequestAPI<any>> => {
    return this.httpClient.post<RequestAPI<any>>(
      `${environment.apiEndpoint}/eco_memberships`,
      ecoMembership
    );
  };

  updateEcoMembership = (
    ecoMembership: EcoMembership
  ): Observable<RequestAPI<any>> => {
    return this.httpClient.put<RequestAPI<any>>(
      `${environment.apiEndpoint}/eco_memberships/${ecoMembership.id}`,
      ecoMembership
    );
  };

  storeContacts = (contact: any): Observable<RequestAPI<Contact[]>> => {
    return this.httpClient.post<RequestAPI<Contact[]>>(
      `${environment.apiEndpoint}/contacts`,
      contact
    );
  };

  updateContacts = (contact: any): Observable<any> => {
    return this.httpClient.put(
      `${environment.apiEndpoint}/contacts/${contact.id}`,
      contact
    );
  };

  deleteEcoMembership = (
    ecoMembership: EcoMembership
  ): Observable<RequestAPI<any>> => {
    return this.httpClient.delete<RequestAPI<any>>(
      `${environment.apiEndpoint}/eco_memberships/${ecoMembership.id}`
    );
  };

  getInvoices = (
    ecoMembership: EcoMembership,
    httpParams: {[k:string]: any} = {}
  ): Observable<RequestPaginateModel2<InvoiceFile>> => {
    const path = `${environment.apiEndpoint}/eco_memberships/${ecoMembership.id}/invoices`;
    const params = new HttpParams({fromObject: httpParams});
    const options = { params };

    return this.httpClient.get<RequestPaginateModel2<InvoiceFile>>(path, options).pipe(
      map((response) => {
        response.data = response.data.map((invoiceFile) => {
          return new InvoiceFile(invoiceFile);
        });

        return response;
      }),
      retry(3)
    );
  };

  getInvoicesList = (
    httpParams: { [k: string]: any } = {}
  ): Observable<RequestPaginateModel2<InvoiceFile>> => {
    const path = `${environment.apiEndpoint}/invoices`;
    const params = new HttpParams({ fromObject: httpParams });
    const options = { params };

    return this.httpClient.get<RequestPaginateModel2<InvoiceFile>>(path, options).pipe(
      map((response) => {
        response.data = response.data.map((invoiceFile) => {
          return new InvoiceFile(invoiceFile);
        });

        return response;
      }),
      retry(3)
    );
  };

  get currentEcoMembership() {
    return this.retrieveFromCache('current_eco_membership');
  }

  set currentEcoMembership(ecoDuty: EcoMembership | undefined) {
    this.cacheIt('current_eco_membership', ecoDuty);
  }
}
