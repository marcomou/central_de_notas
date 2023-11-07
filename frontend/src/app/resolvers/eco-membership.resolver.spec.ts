import { TestBed } from '@angular/core/testing';

import { EcoMembershipResolver } from './eco-membership.resolver';

describe('EcoMembershipResolver', () => {
  let resolver: EcoMembershipResolver;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    resolver = TestBed.inject(EcoMembershipResolver);
  });

  it('should be created', () => {
    expect(resolver).toBeTruthy();
  });
});
