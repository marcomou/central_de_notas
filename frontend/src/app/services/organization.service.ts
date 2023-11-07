import { EventEmitter, Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { from, Observable, of } from 'rxjs';
import { map, retry } from 'rxjs/operators';
import * as _ from 'lodash';

import { BaseService } from './base.service';
import { RequestAPI, RequestPaginateModel2 } from '../models/request';
import { Organization } from '../models/organization';
import { User } from '../models/user';
import { environment } from 'src/environments/environment';
import { EcoDuty } from '../models/eco-duty';
import { CollidenceDashboardData } from '../models/dashboard/collidence';
import {
  ValidatedMassByMaterial,
  ValidatedMassByOperator,
} from '../models/dashboard/mass';
import { NotesByStatus } from '../models/dashboard/notes';
import { EcomembershipsByRoles } from '../models/dashboard/ecoMemberships';
import { InvoiceFile } from '../models/invoice/invoice-file';

@Injectable({
  providedIn: 'root',
})
export class OrganizationService extends BaseService {
  public static currentOrganizationEvent = new EventEmitter<Organization>();

  constructor(private httpClient: HttpClient) {
    super();
  }

  deleteCurrentOrganization(): Observable<any> {
    const id = this.currentOrganization.id;
    const path = `${this.apiEndpoint}/organizations/${id}`;

    return this.httpClient.delete(path).pipe(
      map((response) => {
        let organizations = this.organizations.filter(
          (organization) => organization.id !== id,
          []
        );
        super.cacheIt(Organization.CACHE_ORGANIZATIONS, organizations);

        super.clearCache(Organization.CACHE_MANAGING_ENTITY);

        return response;
      })
    );
  }

  loadUsers(ofRegulatoryBody = false): Observable<User[]> {
    let organization = this.currentManagingEntity;

    if (ofRegulatoryBody) {
      organization = this.currentRegulatoryBody;
    }

    if (!organization) {
      return of([]);
    }

    const path = `${this.apiEndpoint}/organizations/${organization.id}/users`;

    return this.httpClient.get<RequestAPI<User[]>>(path).pipe(
      map((response) => {
        const data = response.data as User[];
        const { CACHE_MANAGING_ENTITY, CACHE_REGULATORY_BODY } = Organization;
        const cacheKey = ofRegulatoryBody
          ? CACHE_REGULATORY_BODY
          : CACHE_MANAGING_ENTITY;

        if (organization) {
          organization.users = data;
          super.cacheIt(cacheKey, organization);
        }

        return data;
      })
    );
  }

  attachUser(id: string): Observable<User[]> {
    let currentOrganization = this.currentOrganization;
    const path = `${this.apiEndpoint}/organizations/${currentOrganization.id}/users/${id}`;
    const body = {};

    return this.httpClient.post<RequestAPI<User[]>>(path, body).pipe(
      map((response) => {
        currentOrganization.users = response.data as User[];

        let organizations = this.organizations.map((organization) => {
          if (organization.id === currentOrganization.id) {
            return currentOrganization;
          }

          return organization;
        });

        super.cacheIt(Organization.CACHE_ORGANIZATIONS, organizations);
        super.cacheIt(Organization.CACHE_MANAGING_ENTITY, currentOrganization);

        return currentOrganization.users;
      })
    );
  }

  get organizations(): Organization[] {
    const organizations = super.retrieveFromCache(
      Organization.CACHE_ORGANIZATIONS
    );

    return organizations.map(
      (organization: { [k: string]: any }) => new Organization(organization)
    );
  }

  get currentOrganization(): Organization {
    let organization = this.currentManagingEntity || this.currentRegulatoryBody;

    if (!organization) {
      organization = this.organizations[0];

      if (organization.isRegulatoryBody) {
        this.currentRegulatoryBody = organization;
      } else {
        this.currentManagingEntity = organization;
      }
    }

    return organization;
  }

  set currentOrganization(organization: Organization) {
    if (organization.isRegulatoryBody) {
      this.currentRegulatoryBody = organization;
      return;
    }

    this.currentManagingEntity = organization;
  }

  get currentRegulatoryBody(): Organization | undefined {
    let organization = super.retrieveFromCache(
      Organization.CACHE_REGULATORY_BODY
    );

    if (!organization) {
      return undefined;
    }

    return new Organization(organization);
  }

  set currentRegulatoryBody(organization: Organization | undefined) {
    if (!organization || !organization.isRegulatoryBody) {
      super.clearCache(EcoDuty.CACHE_CURRENT_ECO_DUTY);
      super.clearCache(Organization.CACHE_MANAGING_ENTITY);
      super.clearCache(Organization.CACHE_REGULATORY_BODY);

      return;
    }

    const currentRegulatoryBody = this.currentRegulatoryBody;
    if (
      !currentRegulatoryBody ||
      currentRegulatoryBody.id !== organization.id
    ) {
      super.clearCache(EcoDuty.CACHE_CURRENT_ECO_DUTY);
      super.clearCache(Organization.CACHE_MANAGING_ENTITY);
    }

    super.cacheIt(Organization.CACHE_REGULATORY_BODY, organization);

    this.updateOrganizationCache(organization);

    OrganizationService.currentOrganizationEvent.emit(organization);
  }

  public clearRegulatoryBody(): void {
    this.clearCache(Organization.CACHE_REGULATORY_BODY);
  }

  get currentManagingEntity(): Organization | undefined {
    let organization: Organization = super.retrieveFromCache(
      Organization.CACHE_MANAGING_ENTITY
    );

    if (!organization) {
      return undefined;
    }

    return new Organization(organization);
  }

  set currentManagingEntity(organization: Organization | undefined) {
    if (!organization) {
      super.clearCache(EcoDuty.CACHE_CURRENT_ECO_DUTY);
      this.clearManagingEntity();

      return;
    }

    const currentManagingEntity = this.currentManagingEntity;
    if (
      !currentManagingEntity ||
      currentManagingEntity.id !== organization.id
    ) {
      super.clearCache(EcoDuty.CACHE_CURRENT_ECO_DUTY);
    }

    super.cacheIt(Organization.CACHE_MANAGING_ENTITY, organization);
    this.updateOrganizationCache(organization);

    OrganizationService.currentOrganizationEvent.emit(organization);
  }

  public clearManagingEntity(): void {
    this.clearCache(Organization.CACHE_MANAGING_ENTITY);
  }

  get users(): Observable<User[]> {
    if (this.currentOrganization?.isRegulatoryBody) {
      return this.regulatoryBodyUsers;
    }

    return this.managingEntityUsers;
  }

  get managingEntityUsers(): Observable<User[]> {
    let users = this.currentManagingEntity?.users;

    if (!users) {
      return this.loadUsers();
    }

    return from([users]);
  }

  get regulatoryBodyUsers(): Observable<User[]> {
    let users = this.currentRegulatoryBody?.users;

    if (!users) {
      return this.loadUsers(true);
    }

    return from([users]);
  }

  removeUserFromCache(organizationId: string, userId: string): void {
    let organizations = this.organizations.map((organization) => {
      if (organization.id === organizationId) {
        organization.users = organization.users?.filter(
          (user) => user.id !== userId,
          []
        );
      }

      return organization;
    });

    super.cacheIt(Organization.CACHE_ORGANIZATIONS, organizations);
    super.clearCache(Organization.CACHE_MANAGING_ENTITY);
  }

  storeOrganization = (organization: Organization): Observable<any> => {
    const path = `${environment.apiEndpoint}/organizations`;
    return this.httpClient.post(path, organization);
  };

  updateOrganization = (organization: Organization): Observable<any> => {
    const path = `${environment.apiEndpoint}/organizations/${organization.id}`;
    return this.httpClient.put(path, organization);
  };

  getAllOrganizations(
    httpParams: { page?: number; q?: string } = {}
  ): Observable<RequestAPI<Organization[]>> {
    const path = `${environment.apiEndpoint}/organizations`;
    
    const params = new HttpParams({ fromObject: httpParams });
    const options = { params };

    return this.httpClient.get<RequestAPI<Organization[]>>(path, options);

    
  }

  getColidences = (
    organizationId?: string,
    httpParams = {}
  ): Observable<RequestAPI<CollidenceDashboardData[]>> => {
    const orgId = organizationId || this.currentOrganization.id;
    const path = `${environment.apiEndpoint}/organizations/${orgId}/dashboard/quantity_collidences_by_organizations`;
    const params = new HttpParams({ fromObject: httpParams });

    return this.httpClient.get<RequestAPI<CollidenceDashboardData[]>>(path, { params });
  };

  getValidatedMassByMaterialType = (
    organizationId?: string,
    httpParams = {}
  ): Observable<RequestAPI<ValidatedMassByMaterial[]>> => {
    const orgId = organizationId || this.currentRegulatoryBody?.id || this.currentManagingEntity?.id;
    const path = `${environment.apiEndpoint}/organizations/${orgId}/dashboard/outgoing_masses`;
    const params = new HttpParams({ fromObject: httpParams });

    return this.httpClient.get<RequestAPI<ValidatedMassByMaterial[]>>(path, { params });
  };

  getEcoMembershipsByRole = (
    organizationId?: string,
    httpParams = {}
  ): Observable<RequestAPI<EcomembershipsByRoles[]>> => {
    const orgId = organizationId || this.currentOrganization.id;
    const path = `${environment.apiEndpoint}/organizations/${orgId}/dashboard/quantity_eco_membership_by_roles`;
    const params = new HttpParams({ fromObject: httpParams });

    return this.httpClient.get<RequestAPI<EcomembershipsByRoles[]>>(path, { params });
  };

  getValidatedMassByOperators = (
    organizationId?: string,
    httpParams = {}
  ): Observable<RequestAPI<ValidatedMassByOperator[]>> => {
    const orgId = organizationId || this.currentOrganization.id;
    const path = `${environment.apiEndpoint}/organizations/${orgId}/dashboard/operation_masses_by_operators`;
    const params = new HttpParams({ fromObject: httpParams });

    return this.httpClient.get<RequestAPI<ValidatedMassByOperator[]>>(path, { params });
  };

  getInvoicesByStatus = (
    organizationId: string,
    httpParams = {}
  ): Observable<RequestAPI<NotesByStatus[]>> => {
    const orgId = organizationId || this.currentOrganization.id;
    const path = `${environment.apiEndpoint}/organizations/${orgId}/dashboard/quantity_invoices_by_status`;
    const params = new HttpParams({ fromObject: httpParams });

    return this.httpClient.get<RequestAPI<NotesByStatus[]>>(path, { params });
  };

  getInvoicesList = (httpParams: { [k: string]: any } = {}): Observable<RequestPaginateModel2<InvoiceFile>> => {
    const id = this.currentOrganization.id;
    // const path = `${environment.apiEndpoint}/invoices`;
    const path = `${environment.apiEndpoint}/organizations/${id}/invoices`;
    const params = new HttpParams({ fromObject: httpParams });
    const options = { params };
    return this.httpClient.get<RequestPaginateModel2<any>>(path, options).pipe(
      map((response) => {
        response.data = response.data.map((res) => {
          let invoiceFile = res;
          // if (res['access_key']) {
          //   invoiceFile = Object.assign({ invoice: res }, res.files?.length > 0 ? res.files[0] : {});
          // }

          if ((invoiceFile.status === 4 || invoiceFile.collidence) && invoiceFile.invoice) {
            invoiceFile.invoice.status = 'collidence';
          }

          return new InvoiceFile(invoiceFile);
        });

        return response;
      }),
      retry(3)
    );
  };

  getInvoiceDetails = (
    organizationId: string,
    invoiceId: string
  ): Observable<RequestAPI<any>> => {
    // const path = `${environment.apiEndpoint}/organizations/${organizationId}/invoices/${invoiceId}`;
    const path = `${environment.apiEndpoint}/invoices/${invoiceId}`;
    return this.httpClient.get<RequestAPI<any>>(path);
  };

  deleteInvoiceFile(fileGuid: string): Observable<any> {
    const path = `${environment.apiEndpoint}/invoices/${fileGuid}`;
    return this.httpClient.delete(path);
  }

  uploadInvoice = (invoices: any) => {
    // const path = `${environment.apiEndpoint}/organizations/${organizationId}/invoices`;
    const path = `${environment.apiEndpoint}/invoices`;
    const headers = new HttpHeaders();
    headers.append('Content-Type', 'text/xml');
    headers.append('Accept', 'text/xml');
    const options = { headers };

    return this.httpClient.post(path, invoices, options);
  };

  getMaterialTypes = () => {
    const path = `${environment.apiEndpoint}/material_types`;
    return this.httpClient.get(path);
  };

  private updateOrganizationCache(organization: Organization): void {
    let organizations = this.organizations.map((org) => {
      if (org.id === organization.id) {
        org = organization;
      }

      return org;
    });

    super.cacheIt(Organization.CACHE_ORGANIZATIONS, organizations);
  }
}
