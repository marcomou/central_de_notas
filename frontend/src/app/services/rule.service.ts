import { Injectable } from '@angular/core';
import { OrganizationService } from './organization.service';

@Injectable({
  providedIn: 'root'
})
export class RuleService {
  constructor(private organizationService: OrganizationService) { }

  get systemRules() {
    return {
      canUpdateGeneralData: !this.organizationService.currentRegulatoryBody,
      canUpdateRepresentativeEntity: !this.organizationService.currentRegulatoryBody,
      canUpdateAdheringCompany: !this.organizationService.currentRegulatoryBody,
      canUpdateLogisticOperator: !this.organizationService.currentRegulatoryBody,
      canUpdateRecycler: !this.organizationService.currentRegulatoryBody
    }
  }
}
