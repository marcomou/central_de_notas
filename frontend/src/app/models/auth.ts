import { Organization } from "./organization";

export class CheckMe {
  id!: string;
  federal_registration!: string;
  name!: string;
  email!: string;
  email_verified_at!: Date;
  organizations!: Organization[];
  created_at!: Date;
  updated_at!: Date;
  deleted_at!: Date;
}

export class SigninSuccess {
  token_type!: string;
  experes_in!: string;
  access_token!: string;
  refresh_token!: string;
}

export class SigninError {
  error!: string;
  error_description!: string;
  message!: string;
}
