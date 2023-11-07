import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import * as _ from 'lodash';

import { BaseService } from './base.service';
import { Organization } from 'src/app/models/organization';
import { User } from 'src/app/models/user';
import { SigninSuccess, SigninError, CheckMe } from '../models/auth';
import { environment } from 'src/environments/environment';
import { RequestAPI } from '../models/request';
import { OrganizationService } from './organization.service';

@Injectable({
  providedIn: 'root',
})
export class AuthService extends BaseService {
  private readonly localStorageKeys = {
    jwt: 'auth.jwt',
    user: 'auth.user',
    roles: 'auth.roles',
    group: 'auth.group',
    organizations: 'auth.organizations',
  };

  constructor(
    private http: HttpClient,
    private organizationService: OrganizationService
  ) {
    super();
  }

  checkMe(): Observable<CheckMe> {
    const path = `${this.apiEndpoint}/profile`;

    return this.http.get<RequestAPI<CheckMe>>(path).pipe(
      map((response) => {
        let data = response.data as CheckMe;

        super.cacheIt('organizations', data.organizations);
        // this.organizationService.currentOrganization;

        data.organizations = data.organizations.map(org => new Organization(org));
        for (const organization of data.organizations) {
          if (organization.isManagingEntity) {
            this.organizationService.currentManagingEntity = organization;
            break;
          }
        }

        let user: Partial<CheckMe> = _.clone(data);
        delete user.organizations;
        super.cacheIt('user', user);

        return data;
      })
    );
  }

  signIn(params: { [k: string]: string }): Observable<any> {
    const path = `${this.apiEndpoint}/oauth/token`;
    params['grant_type'] = 'password';
    params['client_id'] = environment.clientId;
    params['client_secret'] = environment.clientSecret;
    params['scope'] = '';

    return this.http.post<SigninSuccess | SigninError>(path, params).pipe(
      map(async (data) => {
        if (data instanceof SigninError) {
          return false;
        }

        super.cacheIt('access_token', data.access_token);

        return true;
      })
    );
  }

  sendEmailPasswordResetLink(body: { email: string }): Observable<any> {
    const path = `${this.apiEndpoint}/forgot-password`;

    return this.http.post(path, body);
  }

  resetPassword(data: {
    email: string;
    password: string;
    password_confirmation: string;
    token: string;
  }): Observable<any> {
    const path = `${this.apiEndpoint}/reset-password`;

    return this.http.post(path, data);
  }

  signup = (user: any): Observable<any> => {
    return this.http.post(`${this.apiEndpoint}/register`, user);
  };

  logout(): Observable<any> {
    return this.http.post(`${environment.apiEndpoint}/logout`, {});
  }

  public clearCredentialsAndContext() {
    type t = 'jwt' | 'user' | 'roles' | 'group' | 'organizations';

    Object.keys(this.localStorageKeys).forEach((k) => {
      super.clearCache(this.localStorageKeys[k as t]);
    });
  }

  get currentUser(): User {
    return super.retrieveFromCache('user') || {};
  }

  get isAuthenticated(): boolean {
    const isAuthenticated = !!super.retrieveFromCache('access_token');

    if (!isAuthenticated) {
      localStorage.clear();
    }

    return isAuthenticated;
  }

  get jwt(): string {
    return super.retrieveFromCache('access_token');
  }

  get accessToken(): string {
    return super.retrieveFromCache('access_token');
  }

  // public updateCurrentUserData(data: object): User {
  //   const updatedUserData = _.merge({}, this.currentUser, data);
  //   super.cacheIt(this.localStorageKeys.user, updatedUserData);
  //   return new User().deserialize(updatedUserData);
  // }
}
