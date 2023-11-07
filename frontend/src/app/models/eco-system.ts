import { Location } from './location';
import { TimestampsBase } from './timestamps-base';

export class EcoSystem extends TimestampsBase {
  id: string = '';
  location?: Location;
  name: string = '';

  constructor(properties?: {[k:string]: any}) {
    super();

    if (properties) {
      this.id = properties['id'];
      this.name = properties['name'];
      this.location = new Location(properties['location'] || {});
      this.created_at = properties['created_at'];
      this.updated_at = properties['updated_at'];
      this.deleted_at = properties['deleted_at'];
    }
  }
}
