import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DataReverseSystemSubpageComponent } from './data-reverse-system-subpage.component';

describe('DataReverseSystemSubpageComponent', () => {
  let component: DataReverseSystemSubpageComponent;
  let fixture: ComponentFixture<DataReverseSystemSubpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DataReverseSystemSubpageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DataReverseSystemSubpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
