import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DataRecyclerSubpageComponent } from './data-recycler-subpage.component';

describe('DataRecyclerSubpageComponent', () => {
  let component: DataRecyclerSubpageComponent;
  let fixture: ComponentFixture<DataRecyclerSubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DataRecyclerSubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DataRecyclerSubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
