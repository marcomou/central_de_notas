import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ManagingEntityPageComponent } from './managing-entity-page.component';

describe('ManagingEntityPageComponent', () => {
  let component: ManagingEntityPageComponent;
  let fixture: ComponentFixture<ManagingEntityPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ManagingEntityPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ManagingEntityPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
