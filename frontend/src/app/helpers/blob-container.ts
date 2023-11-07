export class BlobContainer {

  private _blob_contents;
  private _content_type: string;
  private _file_name: string;

  constructor(blob_contents, options: {content_type: string, file_name?: string}) {
    this._blob_contents = blob_contents;
    this._content_type  = options['content_type'] || 'application/vnd.ms-excel';
    this._file_name     = options['file_name'] || ('temp' + (new Date).getTime());
  }

  public open(): void {
    let newBlob = new Blob([this._blob_contents], {type: this._content_type});

    // IE doesn't allow using a blob object directly as link href
    // instead it is necessary to use msSaveOrOpenBlob
    if (window.navigator && window.navigator.msSaveOrOpenBlob) {
      window.navigator.msSaveOrOpenBlob(newBlob);
      return;
    }

    // For other browsers:
    // Create a link pointing to the ObjectURL containing the blob.
    const data = window.URL.createObjectURL(newBlob);

    let link = document.createElement('a');
    link.href = data;
    link.download = this._file_name;
    // this is necessary as link.click() does not work on the latest firefox
    link.dispatchEvent(new MouseEvent('click', {bubbles: true, cancelable: true, view: window}));

    setTimeout(function () {
      // For Firefox it is necessary to delay revoking the ObjectURL
      window.URL.revokeObjectURL(data);
      link.remove();
    }, 100);
  }
}
