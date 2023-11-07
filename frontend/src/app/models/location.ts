import { TimestampsBase } from './timestamps-base';

export class Location extends TimestampsBase {
  id: string = '';
  acronym: string = '';
  code: number = 0;
  name: string = '';
  region: string = '';

  constructor(properties?: {[k:string]: any}) {
    super();

    if (properties) {
      this.id = properties['id'];
      this.acronym = properties['acronym'];
      this.code = properties['code'];
      this.name = properties['name'];
      this.region = properties['region'];
      this.created_at = properties['created_at'];
      this.updated_at = properties['updated_at'];
      this.deleted_at = properties['deleted_at'];
    }
  }
}
