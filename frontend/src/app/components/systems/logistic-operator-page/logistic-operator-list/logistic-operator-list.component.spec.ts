import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LogisticOperatorListComponent } from './logistic-operator-list.component';

describe('LogisticOperatorListComponent', () => {
  let component: LogisticOperatorListComponent;
  let fixture: ComponentFixture<LogisticOperatorListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ LogisticOperatorListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(LogisticOperatorListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
