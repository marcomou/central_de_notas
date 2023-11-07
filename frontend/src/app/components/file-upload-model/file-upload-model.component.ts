import { Component, OnInit } from '@angular/core';
import { BsModalRef } from 'ngx-bootstrap/modal';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-file-upload-model',
  templateUrl: './file-upload-model.component.html',
  styleUrls: ['./file-upload-model.component.scss']
})
export class FileUploadModelComponent implements OnInit {

  title: string = 'Documentos';
  typeMessage: string = 'o arquivo';
  type: 'image' | 'file' = 'file';
  acceptTypes: string[] = [];
  allowMultiple: boolean = false;

  onSelected: (files: FileList) => void = () => {};
  
  acceptTypesStr: string = '';
  acceptTypesMessage: string = '';

  readonly fileImg = environment.nhe_icons.file;
  readonly fileUploadImg = environment.nhe_icons.file_upload;

  constructor(public bsModalRef: BsModalRef) { }

  ngOnInit(): void {
    if (this.type === 'image') {
      this.title = 'Imagens';
      this.typeMessage = 'a imagem';
    }

    this.prepareMimeTypes();
  }

  close() {
    this.bsModalRef.hide();

    return false;
  }

  returnFiles(target: any): void {
    let files = target.files as FileList;

    if (files.length > 0) {
      this.onSelected(files);
      this.close();
    }
  }

  private prepareMimeTypes(): void {
    this.acceptTypes.forEach((v, i) => {
      let separator = i === (this.acceptTypes.length - 1) ? ' ou ' : ', ';

      if (i !== 0) {
        this.acceptTypesMessage += separator + v;
      } else {
        this.acceptTypesMessage = v;
      }
    });

    if (this.acceptTypesMessage) {
      this.acceptTypesMessage = `, no formato ${this.acceptTypesMessage}`
    } else {
      this.acceptTypesMessage = '.';
    }

    this.acceptTypesStr = '.' + this.acceptTypes.join(',.');
  }

}
