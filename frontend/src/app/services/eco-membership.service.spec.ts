import { TestBed } from '@angular/core/testing';

import { EcoMembershipService } from './eco-membership.service';

describe('EcoMembershipService', () => {
  let service: EcoMembershipService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(EcoMembershipService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
