import { HttpClient, HttpParams } from '@angular/common/http';
import { EventEmitter, Injectable } from '@angular/core';
import { from, map, Observable, retry } from 'rxjs';
import { environment } from 'src/environments/environment';
import { EcoDuty, ResultByMaterials } from '../models/eco-duty';
import { ResultsByMaterials, ReviewsReport } from '../models/reports/reviews';
import { Metadata, RequestAPI } from '../models/request';
import { BaseService } from './base.service';
import { OrganizationService } from './organization.service';

@Injectable({
  providedIn: 'root',
})
export class EcoDutyService extends BaseService {
  public static currentEcoDutyEvent = new EventEmitter<EcoDuty>();
  public static ecoDutiesEvent = new EventEmitter<EcoDuty[]>();

  constructor(
    private httpClient: HttpClient,
    private organizationService: OrganizationService
  ) {
    super();
  }

  public getEcoDuty = (id: string): Observable<RequestAPI<EcoDuty>> => {
    const path = `${environment.apiEndpoint}/eco_duties/${id}`;
    return this.httpClient.get<RequestAPI<EcoDuty>>(path).pipe(
      map((response) => {
        if (response.data) {
          this.currentEcoDuty = response.data;
        }

        return response;
      })
    );
  };

  public storeEcoDuty = (ecoDuty: EcoDuty): Observable<RequestAPI<any>> => {
    const path = `${environment.apiEndpoint}/eco_duties`;
    return this.httpClient.post<RequestAPI<any>>(path, ecoDuty).pipe(
      map((response) => {
        this.currentEcoDuty = response.data;
        return response;
      })
    );
  };

  public updateEcoDuty = (ecoDuty: EcoDuty): Observable<RequestAPI<any>> => {
    const path = `${environment.apiEndpoint}/eco_duties/${ecoDuty.id}`;
    return this.httpClient.put<RequestAPI<any>>(path, ecoDuty).pipe(
      map((response) => {
        this.currentEcoDuty = response.data;

        return response;
      })
    );
  };

  public deleteEcoDuty = (ecoDuty: string): Observable<RequestAPI<any>> => {
    const path = `${environment.apiEndpoint}/eco_duties/${ecoDuty}`;

    return this.httpClient.delete<RequestAPI<any>>(path);
  };

  public getReviews = (
    ecoDutyId?: string,
    options?: { [k: string]: any }
  ): Observable<RequestAPI<ReviewsReport[]>> => {
    const params = new HttpParams({ fromObject: options });

    return this.httpClient.get<RequestAPI<ReviewsReport[]>>(
      `${environment.apiEndpoint}/eco_duties/${ecoDutyId}/reviews`,
      { params }
    );
  };

  public getResultsByMaterials = (
    ecoDutyId?: string,
    httpParams?: { [k: string]: any }
  ): Observable<RequestAPI<ResultsByMaterials>> => {
    const id = ecoDutyId || this.currentEcoDuty?.id;
    const path = `${environment.apiEndpoint}/eco_duties/${id}/result-by-materials`;
    const params = new HttpParams({ fromObject: httpParams });
    const options = { params };

    return this.httpClient.get<RequestAPI<ResultsByMaterials>>(path, options);
  };

  public getOrganizationEcoDuties(
    options: { [k: string]: any } = {}
  ): Observable<RequestAPI<EcoDuty[]>> {
    const params = new HttpParams({ fromObject: options });
    const { currentRegulatoryBody, currentManagingEntity } = this.organizationService;
    let currentOrganization = currentRegulatoryBody || currentManagingEntity;

    if (!currentOrganization) {
      return from([{
        data: [],
        meta: new Metadata,
      }]);
    }

    const path = `${environment.apiEndpoint}/organizations/${currentOrganization.id}/eco_duties`;
    if (!currentOrganization.eco_duties?.length || currentOrganization.isRegulatoryBody) {
      return this.httpClient.get<RequestAPI<EcoDuty[]>>(path, { params }).pipe(
        map((response) => {
          if (currentOrganization?.isManagingEntity) {
            let ecoDuties = response?.data || [];

            currentOrganization.eco_duties = ecoDuties;
            this.organizationService.currentOrganization = currentOrganization;
          }

          return response;
        })
      );
    }

    return from([{
      data: currentOrganization.eco_duties,
      meta: new Metadata()
    }]);
  }

  public clearCurrentEcoDuty() {
    super.clearCache(EcoDuty.CACHE_CURRENT_ECO_DUTY);
  }

  get currentEcoDuty(): EcoDuty | undefined {
    let ecoDuty = this.retrieveFromCache(EcoDuty.CACHE_CURRENT_ECO_DUTY);

    if (!ecoDuty || !this.organizationService.currentManagingEntity) {
      this.clearCurrentEcoDuty();
      return undefined;
    }

    let ecoDutyModel = new EcoDuty(ecoDuty);
    ecoDutyModel.managing_organization =
      this.organizationService.currentManagingEntity;

    return ecoDutyModel;
  }

  set currentEcoDuty(ecoDuty: EcoDuty | undefined) {
    if (!ecoDuty) {
      this.clearCurrentEcoDuty();
      return;
    }

    this.cacheIt(EcoDuty.CACHE_CURRENT_ECO_DUTY, ecoDuty);

    this.emitCurrentEcoDuty(ecoDuty);
  }

  public postLiabilityDeclaration(materialTypeId: string, massKg: number) {
    const path = `${environment.apiEndpoint}/liability_declarations`;
    const body = {
      eco_duty_id: this.currentEcoDuty?.id || '',
      // eco_membership_id: ecoMembershipId,
      material_type_id: materialTypeId,
      mass_kg: massKg
    };

    return this.httpClient.post(path, body);
  }

  public patchLiabilityDeclaration(liabilityDeclarationId: string, materialTypeId: string, massKg: number) {
    const path = `${environment.apiEndpoint}/liability_declarations/${liabilityDeclarationId}`;
    const body = {
      material_type_id: materialTypeId,
      mass_kg: massKg
    };

    return this.httpClient.patch(path, body);
  }

  public getOperationMasses(params: any = {}): Observable<any> {
    let organization = this.organizationService.currentOrganization;
    const path = `${this.apiEndpoint}/organizations/${organization.id}/dashboard/outgoing_masses`;
    return this.httpClient.get(path, { params }).pipe(
      retry(3),
      map((response: any) => response?.data ?? [])
    );
  }

  private createOrUpdateEcoDutyInList(newEcoDuty: EcoDuty): void {
    let organization = this.organizationService.currentOrganization;

    if (organization.eco_duties === undefined) {
      return;
    }

    const ecoDutyIndex = organization.eco_duties.findIndex(
      (ecoDuty) => ecoDuty.id === newEcoDuty.id
    );
    const ecoDutyExists = ecoDutyIndex !== -1;

    if (ecoDutyExists) {
      organization.eco_duties[ecoDutyIndex] = newEcoDuty;
    } else {
      organization.eco_duties.push(newEcoDuty);
    }

    this.organizationService.currentOrganization = organization;
    this.emitEcoDuties(organization.eco_duties);
  }

  private emitCurrentEcoDuty(ecoDuty: EcoDuty): void {
    this.createOrUpdateEcoDutyInList(ecoDuty);

    EcoDutyService.currentEcoDutyEvent.emit(ecoDuty);
  }

  private emitEcoDuties(ecoDuties: EcoDuty[]): void {
    EcoDutyService.ecoDutiesEvent.emit(ecoDuties);
  }
}
