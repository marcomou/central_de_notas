import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HomologationLogisticOperatorSubpageComponent } from './homologation-logistic-operator-subpage.component';

describe('HomologationLogisticOperatorSubpageComponent', () => {
  let component: HomologationLogisticOperatorSubpageComponent;
  let fixture: ComponentFixture<HomologationLogisticOperatorSubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HomologationLogisticOperatorSubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(HomologationLogisticOperatorSubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
