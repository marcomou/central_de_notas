import { TimestampsBase } from '../timestamps-base';

export class ValidatedMassByMaterial extends TimestampsBase {
  public id!: string;
  public code!: string;
  public extra!: string;
  public mass_kg!: string;
  public name!: string;
  public operation_mass_type!: string;
  public parent_material_id!: string;

  public isIncomingMass: boolean =
    this.operation_mass_type === 'validated_incoming_weight';
}

export class ValidatedMassByOperator {
  public organization_id!: string;
  public eco_membership_id!: string;
  public front_name!: string;
  public legal_name!: string;
  public materials!: [
    { code: string; id: string; mass_kg: string; name: string }
  ];

  public deserialize(input: object) {
    Object.assign(this, input);
    return this;
  }

  public massKgByMaterial = (materialCode: string): string => {
    return (
      this.materials.find(({ code }) => code === materialCode)?.mass_kg ?? '0'
    );
  };
}
