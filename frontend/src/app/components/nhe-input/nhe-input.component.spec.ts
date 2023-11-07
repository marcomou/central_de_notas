import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NheInputComponent } from './nhe-input.component';

describe('NheInputComponent', () => {
  let component: NheInputComponent;
  let fixture: ComponentFixture<NheInputComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NheInputComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NheInputComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
