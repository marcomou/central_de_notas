import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DocumentsRecyclerSubpageComponent } from './documents-recycler-subpage.component';

describe('DocumentsRecyclerSubpageComponent', () => {
  let component: DocumentsRecyclerSubpageComponent;
  let fixture: ComponentFixture<DocumentsRecyclerSubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DocumentsRecyclerSubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentsRecyclerSubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
