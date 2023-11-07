import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NheFileUploadComponent } from './nhe-file-upload.component';

describe('NheFileUploadComponent', () => {
  let component: NheFileUploadComponent;
  let fixture: ComponentFixture<NheFileUploadComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NheFileUploadComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NheFileUploadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
