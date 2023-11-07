import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NheSelectComponent } from './nhe-select.component';

describe('NheSelectComponent', () => {
  let component: NheSelectComponent;
  let fixture: ComponentFixture<NheSelectComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NheSelectComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NheSelectComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
