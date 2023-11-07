import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GeneralDataPageComponent } from './general-data-page.component';

describe('GeneralDataPageComponent', () => {
  let component: GeneralDataPageComponent;
  let fixture: ComponentFixture<GeneralDataPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GeneralDataPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GeneralDataPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
