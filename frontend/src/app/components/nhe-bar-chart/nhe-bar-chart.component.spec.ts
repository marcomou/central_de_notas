import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NheBarChartComponent } from './nhe-bar-chart.component';

describe('NheBarChartComponent', () => {
  let component: NheBarChartComponent;
  let fixture: ComponentFixture<NheBarChartComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NheBarChartComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NheBarChartComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
