import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MassDeclarationAdheringCompaniySubpageComponent } from './mass-declaration-adhering-companiy-subpage.component';

describe('MassDeclarationAdheringCompaniySubpageComponent', () => {
  let component: MassDeclarationAdheringCompaniySubpageComponent;
  let fixture: ComponentFixture<MassDeclarationAdheringCompaniySubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MassDeclarationAdheringCompaniySubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MassDeclarationAdheringCompaniySubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
