import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LogisticOperatorPageComponent } from './logistic-operator-page.component';

describe('LogisticOperatorPageComponent', () => {
  let component: LogisticOperatorPageComponent;
  let fixture: ComponentFixture<LogisticOperatorPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ LogisticOperatorPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(LogisticOperatorPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
