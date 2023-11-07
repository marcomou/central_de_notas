import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdheringCompaniesListComponent } from './adhering-companies-list.component';

describe('AdheringCompaniesListComponent', () => {
  let component: AdheringCompaniesListComponent;
  let fixture: ComponentFixture<AdheringCompaniesListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AdheringCompaniesListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AdheringCompaniesListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
