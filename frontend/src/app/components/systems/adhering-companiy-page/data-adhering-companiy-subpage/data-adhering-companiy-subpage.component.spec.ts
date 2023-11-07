import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DataAdheringCompaniySubpageComponent } from './data-adhering-companiy-subpage.component';

describe('DataAdheringCompaniySubpageComponent', () => {
  let component: DataAdheringCompaniySubpageComponent;
  let fixture: ComponentFixture<DataAdheringCompaniySubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DataAdheringCompaniySubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DataAdheringCompaniySubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
