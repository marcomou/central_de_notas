import { ComponentFixture, TestBed } from '@angular/core/testing';

import { InvoicesLogisticOperatorSubpageComponent } from './invoices-logistic-operator-subpage.component';

describe('InvoicesLogisticOperatorSubpageComponent', () => {
  let component: InvoicesLogisticOperatorSubpageComponent;
  let fixture: ComponentFixture<InvoicesLogisticOperatorSubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ InvoicesLogisticOperatorSubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(InvoicesLogisticOperatorSubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
