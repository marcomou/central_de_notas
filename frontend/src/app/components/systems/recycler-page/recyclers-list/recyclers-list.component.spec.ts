import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RecyclersListComponent } from './recyclers-list.component';

describe('RecyclersListComponent', () => {
  let component: RecyclersListComponent;
  let fixture: ComponentFixture<RecyclersListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RecyclersListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RecyclersListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
