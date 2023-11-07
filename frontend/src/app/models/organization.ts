import * as _ from 'lodash';
import { EcoDutyService } from '../services/eco-duty.service';
import { Base } from './base';
import { EcoDuty } from './eco-duty';
import { LegalType } from './legal-type';
import { User } from './user';

export class Organization extends Base implements OrganizationInterface {
  public static readonly CACHE_REGULATORY_BODY = 'regulatory_body';
  public static readonly CACHE_MANAGING_ENTITY = 'managing_entity';
  public static readonly CACHE_ORGANIZATIONS = 'organizations';

  public id: string = '';
  public getherer: string = '';
  public federal_registration: string = '';
  public legal_name: string = '';
  public front_name: string = '';
  public legal_type_id: string = '';
  public legal_type?: LegalType;
  public users?: User[];
  public eco_duties?: EcoDuty[];
  public is_supervising_organization: boolean = false;
  public is_managing_organization: boolean = false;
  public is_federal_organization: boolean = false;

  constructor(properties?: { [k: string]: any }) {
    super();
    if (properties) {
      this.id = properties['id'];
      this.getherer = properties['getherer_id'] || properties['getherer'];
      this.federal_registration = properties['federal_registration'];
      this.legal_name = properties['legal_name'];
      this.front_name = properties['front_name'];
      this.legal_type_id = properties['legal_type_id'];
      this.users = properties['users'];
      this.eco_duties = (properties['eco_duties'] || []).map(
        (ecoDuty: { [k: string]: any }) => new EcoDuty(ecoDuty)
      );
      this.is_supervising_organization =
        properties['is_supervising_organization'];
      this.is_managing_organization = properties['is_managing_organization'];
      this.is_federal_organization = properties['is_federal_organization'];
      this.created_at = properties['created_at'];
      this.updated_at = properties['updated_at'];
      this.deleted_at = properties['deleted_at'];
    }
  }

  get federalRegistration(): string {
    return this.federal_registration;
  }

  set federalRegistration(federal_registration) {
    this.federal_registration = federal_registration;
  }

  get gethererId(): string {
    return this.getherer;
  }

  set gethererId(getherer) {
    this.getherer = getherer;
  }

  get legalName(): string {
    return this.legal_name;
  }

  set legalName(legalName) {
    this.legal_name = legalName;
  }

  get legalTypeId(): string {
    return this.legal_type_id;
  }

  set legalTypeId(legalTypeId) {
    this.legal_type_id = legalTypeId;
  }

  get ecoDuties(): EcoDuty[] | undefined {
    return this.eco_duties;
  }

  set ecoDuties(ecoDuties: EcoDuty[] | undefined) {
    this.eco_duties = ecoDuties;
  }

  get isManagingEntity(): boolean {
    return this.is_managing_organization;
  }

  get isRegulatoryBody(): boolean {
    return this.is_supervising_organization || this.is_federal_organization;
  }

  get isFederalOrganization(): boolean {
    return this.is_federal_organization;
  }

  get currentEcoDuty(): EcoDuty {
    return super.retrieveFromCache(EcoDuty.CACHE_CURRENT_ECO_DUTY);
  }

  set currentEcoDuty(ecoDuty: EcoDuty) {
    this.cacheIt(EcoDuty.CACHE_CURRENT_ECO_DUTY, ecoDuty);

    this.emitCurrentEcoDuty(ecoDuty);
  }

  public clearCurrentEcoDuty() {
    super.clearCache(EcoDuty.CACHE_CURRENT_ECO_DUTY);
  }

  private createOrUpdateEcoDutyInList(newEcoDuty: EcoDuty): void {
    if (this.eco_duties === undefined) {
      return;
    }

    const ecoDutyIndex = this.eco_duties.findIndex(
      (ecoDuty) => ecoDuty.id === newEcoDuty.id
    );
    const ecoDutyExists = ecoDutyIndex !== -1;

    if (ecoDutyExists) {
      this.eco_duties[ecoDutyIndex] = newEcoDuty;
    } else {
      this.eco_duties.push(newEcoDuty);
    }

    this.emitEcoDuties(this.eco_duties);
  }

  private emitCurrentEcoDuty(ecoDuty: EcoDuty): void {
    this.createOrUpdateEcoDutyInList(ecoDuty);

    EcoDutyService.currentEcoDutyEvent.emit(ecoDuty);
  }

  private emitEcoDuties(ecoDuties: EcoDuty[]): void {
    EcoDutyService.ecoDutiesEvent.emit(ecoDuties);
  }
}

export interface OrganizationInterface {
  id: string;
  getherer?: string;
  federal_registration: string;
  legal_name: string;
  front_name: string;
  legal_type_id: string;
  users?: User[];
  eco_duties?: EcoDuty[];
}
