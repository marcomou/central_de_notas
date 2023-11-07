import { Injectable } from '@angular/core';
import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

import { AuthService } from 'src/app/services/auth.service';
import { environment } from 'src/environments/environment';

@Injectable()
export class ErrorInterceptor implements HttpInterceptor {

  constructor(
    private authService: AuthService) {
  }

  intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    return next.handle(request).pipe(catchError((errHttp) => {
      let errorMessage = (errHttp.error && errHttp.error.message) || errHttp.statusText;

      if (errHttp.status === 401 && request.url.indexOf(environment.apiEndpoint) >= 0) {
        errorMessage = "Não foi possível autenticar o usuário.";
        let wasAuthenticated = this.authService.isAuthenticated;

        this.authService.clearCredentialsAndContext();
        if (wasAuthenticated) {
          location.reload(true); // triggers the navigation to login (AuthGuard)
        }
      }

      return throwError({code: errHttp.status, message: errorMessage});
    }));
  }
}