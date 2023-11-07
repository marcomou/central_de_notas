import { EcoDuty } from './eco-duty';
import { Organization } from './organization';
import { TimestampsBase } from './timestamps-base';

export class EcoMembership extends TimestampsBase {
  public id!: string;
  public member_role!: string;
  public eco_duty_id!: string;
  public member_organization_id!: string;
  public through_membership_id!: string;
  public homologated!: boolean;
  public extra!: {};
  public eco_duty!: EcoDuty;
  public member_organization?: Organization;
  public through_membership: any;
}
