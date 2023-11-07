import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NheTitleComponent } from './nhe-title.component';

describe('NheTitleComponent', () => {
  let component: NheTitleComponent;
  let fixture: ComponentFixture<NheTitleComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NheTitleComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NheTitleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
