import { TimestampsBase } from './timestamps-base';

export class Base extends TimestampsBase {
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

  public deserialize(input: object) {
    Object.assign(this, input);
    return this;
  }
}
