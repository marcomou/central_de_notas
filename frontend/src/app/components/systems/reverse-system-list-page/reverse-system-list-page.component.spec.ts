import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReverseSystemListPageComponent } from './reverse-system-list-page.component';

describe('ReverseSystemListPageComponent', () => {
  let component: ReverseSystemListPageComponent;
  let fixture: ComponentFixture<ReverseSystemListPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ReverseSystemListPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ReverseSystemListPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
