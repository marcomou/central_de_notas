import { TestBed } from '@angular/core/testing';

import { EcoRulesetService } from './eco-ruleset.service';

describe('EcoRulesetService', () => {
  let service: EcoRulesetService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(EcoRulesetService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
