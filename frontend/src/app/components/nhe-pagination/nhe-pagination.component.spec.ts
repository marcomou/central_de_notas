import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NhePaginationComponent } from './nhe-pagination.component';

describe('NhePaginationComponent', () => {
  let component: NhePaginationComponent;
  let fixture: ComponentFixture<NhePaginationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NhePaginationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NhePaginationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
