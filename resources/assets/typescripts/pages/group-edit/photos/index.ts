import * as to from '../../../helpers/TypesConverter';
import * as MapI from '../../../interfaces/Maps';
import * as dbModelI from '../../../interfaces/dbModels';
import * as upload from '../../../services/upload';
import { Status } from '../status';

import * as photos from './photos';

interface TypedParams {
  group: number;
}

interface TypedQuery {}

class Page {
  params: TypedParams;
  query: TypedQuery;
  private pageContainer: JQuery;
  private form: JQuery;
  private fileInput: JQuery;
  private alerts: { failed: JQuery, uploaded: JQuery, generic: JQuery };
  private uploader: upload.Uploader;
  private list: photos.List;

  constructor(...urlParts: MapI.ImmutableStringsMap[]) {
    const [ params/*, query*/ ] = urlParts;
    const group = to.int(params['group']);

    this.params = { group };
    this.query = {};
    this.pageContainer = $('#page-container');
    this.form = this.pageContainer.find('#photos-form');
    this.fileInput = this.form.children('#photos-file-input');
    this.alerts = {
      failed: this.pageContainer.find('#failed-alert'),
      uploaded: this.pageContainer.find('#uploaded-alert'),
      generic: this.pageContainer.find('#generic-alert')
    };

    this.list = new photos.List(this.pageContainer.find('#photos'), group);
  }

  boot() {
    this.list.on(photos.EventType.PHOTO_COVER_FAILURE, this.handlePhotoCoverFailure.bind(this));
    this.list.on(photos.EventType.PHOTO_COVER_SUCCESS, this.hideAlerts.bind(this));
    this.list.on(photos.EventType.PHOTO_DELETE_FAILURE, this.handlePhotoDeleteFailure.bind(this));
    this.list.on(photos.EventType.PHOTO_DELETE_SUCCESS, () => {
      this.updatePhotosCounter();
      this.hideAlerts();
      Status.update(this.params.group);
    });

    this.fileInput.on('change', this.handleFileUpload.bind(this));
    this.form.data({
      'defaultLabel': this.form.children('span').text(),
      'defaultIcon': this.form.children('.icon').attr('class')
    });
  }

  resetForm() {
    (<HTMLFormElement> this.form[0]).reset();
  }

  hideAlerts() {
    for (const alert in this.alerts) {
      this.alerts[<'failed' | 'uploaded' | 'generic'> alert].addClass('hidden');
    }
  }

  enableForm(isEnable: boolean = true, isLoading: boolean = false) {
    const isFormDisabled = !isEnable;

    this.setFormLabel((isLoading) ? 'Uploading...' : undefined);
    this.setFormIcon((isLoading) ? 'notched circle loading icon' : undefined);
    this.form
      .prop('disabled', isFormDisabled)
      .toggleClass('disabled', isFormDisabled);
  }

  setFormIcon(iconClassNames: string = this.form.data('defaultIcon')) {
    this.form.children('i').attr('class', iconClassNames);
  }

  setFormLabel(label: string = this.form.data('defaultLabel')) {
    this.form.children('span').text(label);
  }

  listFailedPhotos(failedUploads: upload.FailedResult[] = []) {
    const failedLength = failedUploads.length;

    if (!failedLength) {
      this.alerts.failed.addClass('hidden');
      return;
    }

    const alert = this.alerts.failed;
    const listItems = failedUploads.map((filed) => (`
      <li>
        <em>\`${filed.filename}\`</em> &mdash; ${filed.errors.slice(-1)}
      </li>
    `));

    alert
      .find('ul')
      .html(listItems.join(''));

    alert.removeClass('hidden')
  }

  listUploadedPhotos(uploaded: dbModelI.GroupPhoto[], allLength: number) {
    const uploadedLength = uploaded.length;
    const alert = this.alerts.uploaded;

    if (!uploadedLength) {
      alert.addClass('hidden');
      return;
    }

    let message = `<p>${uploadedLength} out of ${allLength} photos successfully uploaded.</p>`;

    if (uploadedLength === allLength) {
      message = `<p>${uploadedLength} photo(s) successfully uploaded.</p>`;
    }

    alert
      .html(message)
      .removeClass('hidden');

    for (const photo of uploaded) {
      const thumbnail = photo.thumbnail_src || photo.large_src || photo.original_src;

      this.list.add(new photos.Photo(photo.id, thumbnail, photo.is_cover, this.list));
    }
  }

  updatePhotosCounter() {
    const photosLength = this.list.length;
    const maxPhotosLength = to.int($('input[name="max_photos"]').val());

    this.enableForm(photosLength < maxPhotosLength);
    $('#counter').children('span:first').text(photosLength);
  }

  handlePhotoCoverFailure() {
    this.hideAlerts();
    this.alerts.failed
      .html('<p>There was a problem setting new cover photo. Please try again.</p>')
      .removeClass('hidden');
  }

  handlePhotoDeleteFailure() {
    this.hideAlerts();
    this.alerts.failed
      .html('<p>There was a problem deleting the photo. Please try again.</p>')
      .removeClass('hidden');
  }

  handleProgress(percent: number) {
    this.setFormLabel(`Uploading... ${percent}%`);
  }

  handleUploadFailure() {
    this.alerts.generic
      .html('<p>There was a problem uploading your file(s).</p>')
      .removeClass('hidden');
  }

  async handleFileUpload() {
    const file = <HTMLInputElement> this.fileInput[0];
    const fileList = Array.from(file.files || []);
    const filesLength = fileList.length;

    if (!filesLength || this.uploader && this.uploader.isUploading) {
      return;
    }

    // TODO: Dissalow uploading if `filesLength` > allowed.
    // TODO: Dissalow uploading files > allowed, extract from a `fileList`.

    this.hideAlerts();
    this.enableForm(false, true);
    this.uploader = new upload.Uploader(`/api/v1/classes/${this.params.group}/photos`, 'photos[]', fileList);
    this.uploader.onProgress = this.handleProgress.bind(this);

    try {
      const { uploaded, failed } = await this.uploader.upload();

      this.listUploadedPhotos(<dbModelI.GroupPhoto[]> uploaded, uploaded.length + failed.length);
      this.listFailedPhotos(failed);

    } catch (error) {
      console.log('error', error);
      this.handleUploadFailure();
    }

    this.uploader.dispose();
    this.uploader = null;
    this.updatePhotosCounter();
    this.list.ensureCoverPhoto();
    this.resetForm();
    Status.update(this.params.group);
  }
}

export function init(...urlParts: MapI.ImmutableStringsMap[]) {
  const page = new Page(...urlParts);

  page.boot();
}
