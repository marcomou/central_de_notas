import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map, Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { EcoRuleset } from '../models/eco-ruleset';
import { RequestAPI } from '../models/request';
import { BaseService } from './base.service';

@Injectable({
  providedIn: 'root',
})
export class EcoRulesetService extends BaseService {
  constructor(private httpClient: HttpClient) {
    super();
  }

  getEcoRulesets = (param?: string): Observable<RequestAPI<any>> => {
    return this.httpClient.get<RequestAPI<any>>(
      `${environment.apiEndpoint}/eco_rulesets?${param}`
    ).pipe(
      map(response => {
        this.cacheIt('eco_rulesets', response.data || []);
  
        return response;
      })
    );
  };

  get ecoRuleSets(): EcoRuleset[] {
    return this.retrieveFromCache('eco_rulesets');
  }
}
