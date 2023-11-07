import { HttpErrorResponse } from '@angular/common/http';
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { BsModalService } from 'ngx-bootstrap/modal';
import { AsyncStatus } from 'src/app/models/async-status';
import { DocumentService } from 'src/app/services/document.service';
import { environment } from 'src/environments/environment';
import { FileUploadModelComponent } from '../file-upload-model/file-upload-model.component';
import { NheFileUploadOptionsInterface } from './nhe-file-upload.interface';

@Component({
  selector: 'nhe-file-upload',
  templateUrl: './nhe-file-upload.component.html',
  styleUrls: ['./nhe-file-upload.component.scss'],
})
export class NheFileUploadComponent implements OnInit {
  private optionsDefault: NheFileUploadOptionsInterface = {
    canDownload: false,
    canRedirectToNewGuide: false,
    canUpload: false,
  };

  @Input()
  public acceptTypes: string[] = [];

  @Input('description')
  public descriptionInput: string = '';

  @Input()
  public documentAttachment?: any;

  @Input()
  public ecoMembershipId: string = '';

  @Input()
  public documentTypeId: string = '';

  @Input()
  public isOptional: boolean = false;

  @Input('options')
  public customOptions: Partial<NheFileUploadOptionsInterface> = {};

  @Output()
  public fileUploaded = new EventEmitter();

  @Input()
  public class: string = '';

  public options!: NheFileUploadOptionsInterface;
  public status = AsyncStatus.idle;
  public file?: File;

  constructor(
    private documentService: DocumentService,
    private modalService: BsModalService
  ) {}

  ngOnInit(): void {
    this.prepareFiles();
    this.prepareOptions();
  }

  get isSuccess() {
    return this.status.isSuccess;
  }

  get isEmpty() {
    return !this.documentExists;
  }

  get isError() {
    return this.status.hasError;
  }

  get isPending() {
    return this.status.isProcessing;
  }

  get name() {
    return this.file?.name || this.documentAttachment?.file_name;
  }

  get description() {
    if (!this.descriptionInput) {
      return null;
    }

    return this.descriptionInput + (this.isOptional ? ' (opcional)' : '');
  }

  get documentExists() {
    return !!this.documentAttachment;
  }

  get public_url() {
    const url = this.documentAttachment?.url;
    return url ? environment.endpoint + url : '';
  }

  get ngClass() {
    return {
      success: this.status.isSuccess || this.documentExists,
      error: this.status.hasError,
      pending: this.status.isProcessing,
    };
  }

  public openFileUploadModal() {
    this.modalService.show(FileUploadModelComponent, {
      animated: true,
      class: 'modal-lg',
      initialState: {
        acceptTypes: this.acceptTypes,
        onSelected: (files) => this.setFile(files.item(0) as File),
      },
    });
  }

  public setFile(file: File): void {
    this.file = file;

    this.uploadFile();
  }

  public retry(): void {
    this.uploadFile();
  }

  public remove(): void {
    this.prepareFiles();

    this.file = undefined;
  }

  public uploadFile(): void {
    if (this.status.isProcessing || !this.file) {
      return;
    }

    this.status = AsyncStatus.loading;

    const body = {
      document_type_id: this.documentTypeId,
      eco_membership_id: this.ecoMembershipId,
      file_path: this.file,
    };

    this.documentService.post(body).subscribe({
      next: (document) => {
        this.documentAttachment = document;

        this.fileUploaded.emit(document);
        this.status = AsyncStatus.success;
      },
      error: this.error,
    });
  }

  private error(error: HttpErrorResponse | Error): void {
    const status = error instanceof Error ? 0 : error.status;
    this.status = AsyncStatus.error(status, error.message);
  }

  private prepareOptions(): void {
    this.options = Object.assign(this.optionsDefault, this.customOptions);
  }

  private prepareFiles(): void {
    const success = AsyncStatus.success;
    const idle = AsyncStatus.idle;

    this.status = !!this.documentExists ? success : idle;
  }
}
