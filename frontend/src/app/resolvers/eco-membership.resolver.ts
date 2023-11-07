import { Injectable } from '@angular/core';
import {
  Router, Resolve,
  RouterStateSnapshot,
  ActivatedRouteSnapshot
} from '@angular/router';
import { map, Observable, of } from 'rxjs';
import { EcoMembership } from '../models/eco-membership';
import { EcoMembershipService } from '../services/eco-membership.service';

@Injectable({
  providedIn: 'root'
})
export class EcoMembershipResolver implements Resolve<EcoMembership|undefined> {
  constructor(
    private router: Router,
    private ecoMembershipService: EcoMembershipService
  ) {}

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<EcoMembership|undefined> {
    const ecoMembership = this.ecoMembershipService.currentEcoMembership;
    const id = route.params['id'];

    if (!ecoMembership) {
      return this.ecoMembershipService.getEcoMemberShip(id).pipe(map(({ data }) => {
        if (data) {
          this.ecoMembershipService.currentEcoMembership = data;
        }
        return data;
      }));
    }

    return of(ecoMembership);
  }
}
