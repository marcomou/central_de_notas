import { TestBed } from '@angular/core/testing';

import { EcoDutyService } from './eco-duty.service';

describe('EcoDutyService', () => {
  let service: EcoDutyService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(EcoDutyService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
