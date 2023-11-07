import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

import { AuthService } from 'src/app/services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {

  constructor(
    private router: Router,
    private authService: AuthService) { }

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {

    if (this.authService.isAuthenticated) {

      if (this._isProduction() && next.data && next.data.disableOnProduction) {
        console.warn("This area is not enabled in the production environment");
        this.router.navigate(['/']);
        return false;
      }

      return true;
    }

    this.router.navigate(['/acesso'], {
      queryParams: {'destino': state.url},
    });
    return false;
  }

  private _isProduction(): boolean {
    return !!environment.production;
    // return !!environment.simulateProd;
  }
}
