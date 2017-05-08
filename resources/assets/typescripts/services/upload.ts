import * as cookies from './cookies';
import * as to from '../helpers/TypesConverter';

export const MAX_SIMULTANEOUS_UPLOADS = 6;

export interface ProgressCallback {
  (percentCompleted: number): void;
}

export class FailedResult {
  constructor(
    public filename: string,
    public errors: string[]
  ) {}
}

export class Result {
  constructor(
    public uploaded: Object[] = [],
    public failed: FailedResult[] = []
  ) {}

  get length(): number {
    return this.uploaded.length + this.failed.length;
  }
}

export class Uploader {
  private endpoint: string;
  private formKey: string;
  private fileList: File[];
  private result: Result;
  private xhr: JQueryXHR;
  private simultaneousXhr: number;
  private onProgressCallback: ProgressCallback;
  public isUploading: boolean;

  constructor(endpoint: string, formKey: string, fileList: File[] | FileList) {
    this.endpoint = endpoint;
    this.formKey = formKey;
    this.result = new Result();
    this.fileList = Array.from(fileList);
    this.isUploading = false;
    this.simultaneousXhr = 1;
    this.onProgressCallback = () => {};
  }

  set simultaneousXhrRequests(count: number) {
    this.simultaneousXhr = Math.min(count, MAX_SIMULTANEOUS_UPLOADS);
  }

  set onProgress(callback: ProgressCallback) {
    this.onProgressCallback = callback;
  }

  dispose() {
    if (this.xhr) {
      this.xhr.abort();
      this.xhr = null;
    }

    this.onProgressCallback = null;
    this.fileList = null;
    this.result = null;
  }

  addFiles(fileList: File[] | FileList) {
    this.fileList = Array.from(fileList);
  }

  async upload(): Promise<Result> {
    const filesLength = this.fileList.length;

    if (filesLength) {
      this.isUploading = true;

      for (let i = 0; i < filesLength;) {
        await this.triggerUpload(this.fileList.slice(i, i += this.simultaneousXhr));
        this.onProgressCallback(to.int(i / filesLength * 100));
      }
    }

    this.fileList = [];
    this.isUploading = false;

    return this.result;
  }

  private async triggerUpload(files: File[]) {
      const formData = this.buildFormData(this.formKey, files);

      try {
        const result = await this.uploadToServer(formData);

        this.result.uploaded.push(...result.uploaded);
        this.result.failed.push(...result.failed);

      } catch(error) {
        const failed = files.map((file) => new FailedResult(file.name, [error.message]));

        this.result.failed.push(...failed);
      }
  }

  private buildFormData(key: string, files: File[], formData: FormData = new FormData()) {
    for (const file of files) {
      formData.append(key, file);
    }

    return formData;
  }

  private uploadToServer(data: FormData): Promise<Result> {
    return new Promise((resolve, reject) => {
      this.xhr = $.ajax({
        url: this.endpoint,
        method: 'POST',
        headers: {
          'X-XSRF-TOKEN': cookies.get('XSRF-TOKEN')
        },
        data,
        cache: false,
        contentType: false,
        processData: false,
        success: resolve,
        error: () => reject(new Error('Generic error when file uploading.')),
        complete: () => {
          this.xhr = null;
        }
      });
    });
  }
}
