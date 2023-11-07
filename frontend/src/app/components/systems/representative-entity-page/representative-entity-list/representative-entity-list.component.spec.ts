import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RepresentativeEntityListComponent } from './representative-entity-list.component';

describe('RepresentativeEntityListComponent', () => {
  let component: RepresentativeEntityListComponent;
  let fixture: ComponentFixture<RepresentativeEntityListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RepresentativeEntityListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RepresentativeEntityListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
