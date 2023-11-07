import { BaseModel } from 'src/app/models/common/base-model';
import { TimestampsBase } from '../timestamps-base';

export class ReviewsReport extends TimestampsBase {
  public id!: string;
  public external_id!: string;
  public comments!: string;
  public reviewed_at!: string;
  public sequence_number!: string | number;
  public type!: string;
  public metadata!: any;
  public reviewer_user?: {
    id: string;
    name: string;
    email: string;
    federal_registration: string;
  };
}

export class ResultsByMaterials extends BaseModel {
  public defined_goals_percent!: {
    glass: number;
    metal: number;
    paper: number;
    plastic: number;
  };
  public defined_goals_weight_mass: MaterialGoals[] = [new MaterialGoals()];
  public liability_declarations: MaterialGoals[] = [new MaterialGoals()];
  public validated_outgoing_operation_masses: MaterialGoals[] = [
    new MaterialGoals(),
  ];

  public validatedMasses = (code?: string) => {
    return {
      ...this.validated_outgoing_operation_masses.find(
        (validatedMasses) => validatedMasses.code === code
      ),
      total: this.validated_outgoing_operation_masses
        .map((e): number => parseFloat(e.mass_kg))
        .reduce((accumulator, curr) => accumulator + curr)
        .toFixed(2),
    };
  };

  public liabilityDeclarations = (code?: string) => {
    return {
      ...this.liability_declarations.find(
        (liabilityDeclarations) => liabilityDeclarations.code === code
      ),
      total: this.liability_declarations
        .map((e): number => parseFloat(e.mass_kg))
        .reduce((accumulator, curr) => accumulator + curr),
    };
  };
  public definedGoals = (code?: string) => {
    return {
      ...this.defined_goals_weight_mass.find(
        (definedGoals) => definedGoals.code === code
      ),
      total: this.defined_goals_weight_mass
        .map((e): number => parseFloat(e.mass_kg))
        .reduce((accumulator, curr) => accumulator + curr)
        .toFixed(2),
    };
  };
}

export class MaterialGoals extends BaseModel {
  // public id!: string;
  public done?: boolean;
  public code!: string;
  public mass_kg!: string;
  public name!: string;
  public material_type_id?: string;
}
