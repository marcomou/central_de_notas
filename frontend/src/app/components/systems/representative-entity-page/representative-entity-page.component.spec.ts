import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RepresentativeEntityPageComponent } from './representative-entity-page.component';

describe('RepresentativeEntityPageComponent', () => {
  let component: RepresentativeEntityPageComponent;
  let fixture: ComponentFixture<RepresentativeEntityPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RepresentativeEntityPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RepresentativeEntityPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
