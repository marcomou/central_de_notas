import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DataLogisticOperatorSubpageComponent } from './data-logistic-operator-subpage.component';

describe('DataLogisticOperatorSubpageComponent', () => {
  let component: DataLogisticOperatorSubpageComponent;
  let fixture: ComponentFixture<DataLogisticOperatorSubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DataLogisticOperatorSubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DataLogisticOperatorSubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
