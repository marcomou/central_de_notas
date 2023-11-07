import { EcoSystem } from './eco-system';
import { TimestampsBase } from './timestamps-base';

export class EcoRuleset extends TimestampsBase {
  id!: string;
  duty_year!: number | string;
  rules!: string[];
  eco_system?: EcoSystem;

  constructor(properties?: {[k:string]: any}) {
    super();

    if (properties) {
      this.id = properties['id'];
      this.duty_year = properties['duty_year'];
      this.rules = properties['rules'];
      this.eco_system = new EcoSystem(properties['eco_system'] || properties['ecoSystem'] || {});
      this.created_at = properties['created_at'];
      this.updated_at = properties['updated_at'];
      this.deleted_at = properties['deleted_at'];
    }
  }
}
