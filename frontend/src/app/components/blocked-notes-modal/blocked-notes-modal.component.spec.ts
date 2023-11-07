import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BlockedNotesModalComponent } from './blocked-notes-modal.component';

describe('BlockedNotesModalComponent', () => {
  let component: BlockedNotesModalComponent;
  let fixture: ComponentFixture<BlockedNotesModalComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BlockedNotesModalComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BlockedNotesModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
