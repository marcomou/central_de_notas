import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdheringCompaniyPageComponent } from './adhering-companiy-page.component';

describe('AdheringCompaniyPageComponent', () => {
  let component: AdheringCompaniyPageComponent;
  let fixture: ComponentFixture<AdheringCompaniyPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AdheringCompaniyPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AdheringCompaniyPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
