import { Injectable } from '@angular/core';
import {
  Router, Resolve,
  RouterStateSnapshot,
  ActivatedRouteSnapshot
} from '@angular/router';
import { Observable, of, map } from 'rxjs';
import { EcoDuty } from '../models/eco-duty';
import { EcoDutyService } from '../services/eco-duty.service';

@Injectable({
  providedIn: 'root'
})
export class EcoDutyResolver implements Resolve<EcoDuty|undefined> {
  constructor(private ecoDutyService: EcoDutyService) {}

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<EcoDuty|undefined> {
    const ecoDuty = this.ecoDutyService.currentEcoDuty;

    if (ecoDuty?.id && !ecoDuty?.eco_ruleset) {
      return this.ecoDutyService.getEcoDuty(ecoDuty.id).pipe(map(response => response.data as EcoDuty));
    }

    return of(ecoDuty);
  }
}
