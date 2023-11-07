import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DocumentsLogisticOperatorSubpageComponent } from './documents-logistic-operator-subpage.component';

describe('DocumentsLogisticOperatorSubpageComponent', () => {
  let component: DocumentsLogisticOperatorSubpageComponent;
  let fixture: ComponentFixture<DocumentsLogisticOperatorSubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DocumentsLogisticOperatorSubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentsLogisticOperatorSubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
