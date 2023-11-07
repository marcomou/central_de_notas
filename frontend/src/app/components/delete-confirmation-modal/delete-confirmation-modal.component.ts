import { Component, OnInit } from '@angular/core';
import { BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-delete-confirmation-modal',
  templateUrl: './delete-confirmation-modal.component.html',
  styleUrls: ['./delete-confirmation-modal.component.scss'],
})
export class DeleteConfirmationModalComponent implements OnInit {
  public confirmed: (confirmed: boolean) => void = () => {};
  public text: string = '';
  private confirmText = 'excluir';

  constructor(public bsModalRef: BsModalRef) {}

  ngOnInit(): void {}

  close() {
    this.bsModalRef.hide();
    return false;
  }

  isEqual = (): boolean => {
    return this.text.toUpperCase() === this.confirmText.toUpperCase();
  };

  confirm = () => {
    this.confirmed(true);
    this.close();
  };
}
