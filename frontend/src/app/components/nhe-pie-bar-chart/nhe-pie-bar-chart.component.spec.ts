import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NhePieBarChartComponent } from './nhe-pie-bar-chart.component';

describe('NhePieBarChartComponent', () => {
  let component: NhePieBarChartComponent;
  let fixture: ComponentFixture<NhePieBarChartComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NhePieBarChartComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NhePieBarChartComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
