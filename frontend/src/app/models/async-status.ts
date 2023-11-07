export class AsyncStatus {
  private _id: string;
  private _message: string;
  private _running: boolean = false;
  private _success: boolean = false;
  private _errorCode: number = 0;

  constructor(id: string, options: {[k:string]: any} = {}) {
    this._id = id;
    this._message = options['message'] || null;
    this._running = options['running'] || false;
    this._success = options['success'] || false;
    this._errorCode = options['errorCode'] || 0;
  }

  get id(): string {
    return this._id;
  }

  get message(): string {
    return this._message;
  }

  get isProcessing(): boolean {
    return this._running;
  }

  get isSuccess(): boolean {
    return this._success;
  }

  get errorCode(): number {
    return this._errorCode;
  }

  get hasError(): boolean {
    return !!this.errorCode || this._id === 'error';
  }

  public static error(errorCode: number, message: string|null = null): AsyncStatus {
    return new AsyncStatus('error', {errorCode: errorCode, message: message});
  }

  public static readonly idle = new AsyncStatus('idle');
  public static readonly success = new AsyncStatus('success', {success: 1});
  public static readonly loading = new AsyncStatus('loading', {running: 1});
}
