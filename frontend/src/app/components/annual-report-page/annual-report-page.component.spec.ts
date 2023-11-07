import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AnnualReportPageComponent } from './annual-report-page.component';

describe('AnnualReportPageComponent', () => {
  let component: AnnualReportPageComponent;
  let fixture: ComponentFixture<AnnualReportPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AnnualReportPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AnnualReportPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
