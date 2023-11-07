import { HttpParams } from '@angular/common/http';
import * as _ from 'lodash';

import { Request } from './request';

export interface GetRequest extends Request {
  optionsWithParams(): {params: HttpParams};
  cacheableResponseKey(): string;
  withDeleted(bool: boolean): void;
  onlyDeleted(bool: boolean): void;
  appliableParams(): {[k: string]: string|number};
}

export abstract class AppGetRequest implements GetRequest {

  protected _path!: string;
  protected _params?: object;
  protected _acceptableParams!: Array<string>;

  protected _with_deleted: boolean = false;
  protected _only_deleted: boolean = false;

  public path(): string {
    return this._path;
  }

  constructor(params?: object) {
    this._params = params;
  }

  public optionsWithParams(): {params: HttpParams, headers?: any} {
    let httpParams = _.reduce(this.appliableParams(), (carry: HttpParams, value: any, key: string) => {

      return ('' !== value && undefined != value) ? carry.set(key, value) : carry;

    }, new HttpParams);

    httpParams = this._applySoftDeletionFilters(httpParams);

    return {params: httpParams};
  }

  public cacheableResponseKey(): string {
    let key = this.path();
    const paramsJson = this._jsonOfAppliableParams();
    if (paramsJson && paramsJson !== '{}') {
      key += '?' + paramsJson;
    }
    return key;
  }

  public withDeleted(bool = true) {
    this._with_deleted = !!bool;
    this._only_deleted = !bool;
  }

  public onlyDeleted(bool = true) {
    this._only_deleted = !!bool;
    this._with_deleted = !bool;
  }

  public appliableParams() {
    const appliableTypes = ['boolean', 'number', 'string'];
    const acceptableParams = ['with_deleted', 'only_deleted'].concat(this._acceptableParams);

    return _.pickBy(this._params || {}, (value, key) => {
      return (true
          && _.includes(appliableTypes, (typeof value)) // no objects or functions
          && _.includes(acceptableParams, key)
          && true);
    }) as { [k:string]: any };
  }

  protected _applySoftDeletionFilters(httpParams: HttpParams): HttpParams {
    if (this._with_deleted) {
      httpParams = httpParams.set('with_deleted', '1');
    }
    else if (this._only_deleted) {
      httpParams = httpParams.set('only_deleted', '1');
    }
    return httpParams;
  }

  private _jsonOfAppliableParams() {
    let params = this.appliableParams();
    return JSON.stringify(params, Object.keys(params).sort());
  }
}
