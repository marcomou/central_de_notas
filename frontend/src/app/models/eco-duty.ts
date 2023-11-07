import { Base } from './base';
import { EcoRuleset } from './eco-ruleset';
import { Organization } from './organization';
export class EcoDuty extends Base implements EcoDutyInterface {
  public static readonly CACHE_CURRENT_ECO_DUTY = 'current_eco_duty';

  public id: string = '';
  public managing_code: string = '';
  public managing_organization?: Organization;
  public eco_ruleset!: EcoRuleset;
  public metadata: {
    system_name?: string;
    residual_object_system?: string;
    url_page?: string;
    url_name?: string;
    description?: string;
    interloctor?: {
      name?: string;
      document?: string;
      registration_document?: string;
      phone?: string;
      email?: string;
    };
    operational_data?: {
      support_screening_centers?: boolean;
      recycling_credit_system?: boolean;
      recycling_credit_system_residual_percent?: {
        papper?: number;
        plastic?: number;
        glass?: number;
        metal?: number;
      };
    };
  } = {};
  public status: string = '';

  constructor(properties?: {[k:string]: any}) {
    super();

    if (properties) {
      this.id = properties['id'];
      this.managing_code = properties['managing_code'];
      this.managing_organization = properties['managing_organization'];
      this.eco_ruleset = properties['eco_ruleset'];
      this.metadata = properties['metadata'];
      this.status = properties['status'];
      this.created_at = properties['created_at'];
      this.updated_at = properties['updated_at'];
      this.deleted_at = properties['deleted_at'];
    }
  }
}

export interface EcoDutyInterface {
  id: string;
  managing_code: string;
  managing_organization?: Organization;
  eco_ruleset: EcoRuleset;
  metadata: {
    system_name?: string;
    residual_object_system?: string;
    url_page?: string;
    url_name?: string;
    description?: string;
    interloctor?: {
      name?: string;
      document?: string;
      registration_document?: string;
      phone?: string;
      email?: string;
    };
    operational_data?: {
      support_screening_centers?: boolean;
      recycling_credit_system?: boolean;
      recycling_credit_system_residual_percent?: {
        papper?: number;
        plastic?: number;
        glass?: number;
        metal?: number;
      };
    };
  };
  status: string;
}

export interface DefinedGoalsPercent {
  glass: number;
  metal: number;
  paper: number;
  plastic: number;
  [k:string]: number;
}
export interface DefinedGoalsWeightMass {
  id: string;
  code: string;
  name: string;
  mass_kg: number;
  done: boolean;
}
export interface LiabilityDeclarations {
  id: string;
  code: string;
  name: string;
  mass_kg: number;
  material_type_id: string;
}
export interface ValidatedOutgoingOperationMasses {
  id: string;
  code: string;
  name: string;
  mass_kg: number;
}

export interface ResultByMaterials {
  defined_goals_percent: DefinedGoalsPercent;
  defined_goals_weight_mass: DefinedGoalsWeightMass[];
  liability_declarations: LiabilityDeclarations[];
  validated_outgoing_operation_masses: ValidatedOutgoingOperationMasses[];
}
