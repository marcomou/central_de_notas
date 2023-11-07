import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NheEcoDutyStatusComponent } from './nhe-eco-duty-status.component';

describe('NheEcoDutyStatusComponent', () => {
  let component: NheEcoDutyStatusComponent;
  let fixture: ComponentFixture<NheEcoDutyStatusComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NheEcoDutyStatusComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NheEcoDutyStatusComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
