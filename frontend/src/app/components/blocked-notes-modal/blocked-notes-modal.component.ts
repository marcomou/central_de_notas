import { Component, OnInit } from '@angular/core';
import { BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-blocked-notes-modal',
  templateUrl: './blocked-notes-modal.component.html',
  styleUrls: ['./blocked-notes-modal.component.scss'],
})
export class BlockedNotesModalComponent implements OnInit {
  title: string = 'Documentos';
  allowMultiple: boolean = false;
  public blockedNotesList: Array<any> = [];
  onSelected: (files: FileList) => void = () => {};
  constructor(public bsModalRef: BsModalRef) {}

  ngOnInit(): void {
    this.title = 'Imagens';
  }

  close() {
    this.bsModalRef.hide();
    return false;
  }
}
