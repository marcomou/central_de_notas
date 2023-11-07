import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RecyclerPageComponent } from './recycler-page.component';

describe('RecyclerPageComponent', () => {
  let component: RecyclerPageComponent;
  let fixture: ComponentFixture<RecyclerPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RecyclerPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RecyclerPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
