import { TimestampsBase } from './timestamps-base';

export class Contact extends TimestampsBase {
  public id!: string;
  public eco_membership_id!: string;
  public role!: string;
  public name?: string;
  public document?: string;
  public email?: string;
  public phone?: string;
}
