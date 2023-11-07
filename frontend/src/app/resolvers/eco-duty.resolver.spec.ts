import { TestBed } from '@angular/core/testing';

import { EcoDutyResolver } from './eco-duty.resolver';

describe('EcoDutyResolver', () => {
  let resolver: EcoDutyResolver;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    resolver = TestBed.inject(EcoDutyResolver);
  });

  it('should be created', () => {
    expect(resolver).toBeTruthy();
  });
});
