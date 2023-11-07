import { Component, OnInit } from '@angular/core';
import { BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-error-modal',
  templateUrl: './error-modal.component.html',
  styleUrls: ['./error-modal.component.scss'],
})
export class ErrorModalComponent implements OnInit {
  constructor(public bsModalRef: BsModalRef) {}

  ngOnInit(): void {}

  close() {
    this.bsModalRef.hide();
    return false;
  }
}
