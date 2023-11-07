import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map, Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { LegalType } from '../models/legal-type';
import { Organization } from '../models/organization';
import { RequestAPI } from '../models/request';
import { User } from '../models/user';
import { AuthService } from './auth.service';
import { OrganizationService } from './organization.service';

@Injectable({
  providedIn: 'root',
})
export class UserService {
  constructor(
    private httpClient: HttpClient,
    private organizationService: OrganizationService,
    private authService: AuthService
  ) { }

  public getLegalTypes = (): Observable<RequestAPI<LegalType[]>> => {
    return this.httpClient.get<RequestAPI<LegalType[]>>(
      `${environment.apiEndpoint}/legal_types`
    );
  };

  public signup = (user: Organization & User): Observable<any> => {
    return this.httpClient.post(`${environment.apiEndpoint}/register`, user);
  };

  public create(params: { name: string, federal_registration: string, email: string, password: string }): Observable<User> {
    return this.httpClient.post<RequestAPI<User>>(`${environment.apiEndpoint}/users`, params).pipe(map(response => response.data as User));
  }

  public delete(id: string, orgId?: string): Observable<any> {
    const sameOrganization = this.organizationService.currentOrganization.id === orgId;
    const sameUser = this.authService.currentUser.id === id;

    return this.httpClient.delete(`${environment.apiEndpoint}/organizations/${orgId}/users/${id}`).pipe(response => {
      if (sameOrganization && sameUser) {
        this.authService.logout().subscribe();
        localStorage.clear();
        window.location.reload();
        return response;
      }

      if (orgId) {
        this.organizationService.removeUserFromCache(orgId, id);
      }

      return response;
    });
  }
}
