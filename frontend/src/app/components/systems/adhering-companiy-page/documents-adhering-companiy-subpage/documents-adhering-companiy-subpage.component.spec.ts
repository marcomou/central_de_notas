import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DocumentsAdheringCompaniySubpageComponent } from './documents-adhering-companiy-subpage.component';

describe('DocumentsAdheringCompaniySubpageComponent', () => {
  let component: DocumentsAdheringCompaniySubpageComponent;
  let fixture: ComponentFixture<DocumentsAdheringCompaniySubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DocumentsAdheringCompaniySubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentsAdheringCompaniySubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
