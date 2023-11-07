import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, from } from 'rxjs';
import { JwtDecode } from 'src/app/helpers/jwt-decode';

import { AuthService } from 'src/app/services/auth.service';
import { environment } from 'src/environments/environment';
import { Router } from '@angular/router';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor(
    private authService: AuthService,
    private _router: Router) { }

  intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    let requestHeaders = {}

    if (request.url.indexOf(environment.apiEndpoint) >= 0) {
      requestHeaders = this._addContentTypeIfNotPresent(requestHeaders, request);
      requestHeaders = this._addApiTokenHeaders(requestHeaders, request);
    }

    let modifiedRequest = this._addHeadersToRequest(requestHeaders, request);

    return next.handle(modifiedRequest);
  }

  // ---------------------------------------------------------------- //
  //                            _private
  // ---------------------------------------------------------------- //

  private _addApiTokenHeaders(requestHeaders: any, request: HttpRequest<any>) {
    requestHeaders['api-token'] = environment.apiToken;

    if (this.authService.isAuthenticated) {
      requestHeaders['X-Access-Token'] = `Bearer ${this.authService.jwt}`;
    }
  

    return requestHeaders;
  }

  private _addHeadersToRequest(requestHeaders: any, request: HttpRequest<any>) {
    return request.clone({
      setHeaders: requestHeaders,
    });
  }

  private _addContentTypeIfNotPresent(requestHeaders: any, request: HttpRequest<any>) {
    if (!request.headers.has('Content-Type')) {
      requestHeaders['Content-Type'] = 'application/json';
    }

    return requestHeaders;
  }
}