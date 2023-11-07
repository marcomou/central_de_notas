import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NhePieChartComponent } from './nhe-pie-chart.component';

describe('NhePieChartComponent', () => {
  let component: NhePieChartComponent;
  let fixture: ComponentFixture<NhePieChartComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NhePieChartComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NhePieChartComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
