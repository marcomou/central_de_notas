import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NheTabComponent } from './nhe-tab.component';

describe('NheTabComponent', () => {
  let component: NheTabComponent;
  let fixture: ComponentFixture<NheTabComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NheTabComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NheTabComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
