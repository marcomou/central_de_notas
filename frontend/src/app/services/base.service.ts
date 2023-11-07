import { environment } from 'src/environments/environment';
import { GetRequest } from 'src/app/models/common/get-request';

export class BaseService {
  protected readonly apiEndpoint = environment.apiEndpoint;

  protected readonly getRequestCachePrefix = 'req_resp';

  constructor() {}

  //------------------------------------------------------------------------------------------------
  // Common cache methods

  protected cacheIt(
    key: string,
    data: any,
    duration_ms: number = 24 * 60 * 60 * 1000,
    meta_data?: { path: string; params: any }
  ) {
    const now_ms = new Date().getTime();

    let wrapped: any = {
      data: data,
      expiresAt: now_ms + duration_ms,
      expireStr: new Date(now_ms + duration_ms).toISOString(),
    };

    if (meta_data) {
      wrapped['path'] = meta_data['path'];
      wrapped['params'] = meta_data['params'];
    }

    localStorage.setItem(key, JSON.stringify(wrapped));
  }

  protected retrieveFromCache(key: string, acceptExpired: boolean = false) {
    const now_ms = new Date().getTime();
    const item = localStorage.getItem(key);
    const wrapped = item ? JSON.parse(item) : {};

    if ((acceptExpired || now_ms < wrapped['expiresAt']) && wrapped['data']) {
      return wrapped['data'];
    }

    return null;
  }

  protected clearCache(key: string) {
    localStorage.removeItem(key);
  }

  //------------------------------------------------------------------------------------------------
  // GET Request-related cache methods

  protected retrieveFromGetRequestCache(
    request: GetRequest,
    acceptExpired: boolean = false
  ) {
    const key = this.getRequestCacheKey(request);
    return this.retrieveFromCache(key, acceptExpired);
  }

  protected cacheGetRequest(
    request: GetRequest,
    data: any,
    duration_ms: number = 60 * 1000
  ) {
    const key = this.getRequestCacheKey(request);
    let metadata: any = { path: request.path() };
    let reqParams = request.appliableParams();
    if ('{}' !== JSON.stringify(reqParams)) {
      metadata['params'] = reqParams;
    }
    this.cacheIt(key, data, duration_ms, metadata);
  }

  protected getRequestCacheKey(request: GetRequest): string {
    return (
      this.getRequestCachePrefix + '[' + request.cacheableResponseKey() + ']'
    );
  }

  protected clearGetRequestFromCache(request: GetRequest) {
    const key = this.getRequestCacheKey(request);
    this.clearCache(key);
  }
}
