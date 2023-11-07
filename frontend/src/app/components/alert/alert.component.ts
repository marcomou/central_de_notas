import { Component, OnInit } from '@angular/core';
import { BsModalRef } from 'ngx-bootstrap/modal';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-alert',
  templateUrl: './alert.component.html',
  styleUrls: ['./alert.component.scss']
})
export class AlertComponent implements OnInit {

  title?: string;
  message?: string;
  status: 'success'|'error'|'question' = 'success';
  ngClassStatus!: {[k:string]: any};
  ngClassTitle!: {[k:string]: any};

  buttonPrimaryText = 'OK';
  onPrimary: () => void = () => {};

  buttonCancel = {
    enabled: false,
    text: 'Cancelar'
  }
  
  readonly imgs = {
    success: environment.nhe_icons.ok,
    error: '',
    question: ''
  };

  private readonly titleDefault = {
    success: 'Sucesso!',
    error: 'Erro!',
    question: ''
  };

  constructor(public bsModalRef: BsModalRef) { }

  ngOnInit(): void {
    this.ngClassStatus = {
      'btn-primary': this.status === 'success',
      'btn-danger': this.status === 'error',
      'btn-outline-secondary': this.status === 'question'
    }

    this.ngClassTitle = {
      'text-primary': this.status === 'success',
      'text-danger': this.status === 'error',
      'text-secondary': this.status === 'question'
    }

    if (!this.title) {
      this.title = this.titleDefault[this.status];
    }

    if (this.status === 'question') {
      this.buttonCancel.enabled = true;
    }
  }

  close() {
    this.bsModalRef.hide();

    return false;
  }

  onClickPrimary() {
    this.bsModalRef.hide();

    this.onPrimary();

    return true;
  }

}
