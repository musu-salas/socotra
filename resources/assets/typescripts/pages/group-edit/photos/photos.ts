import * as to from '../../../helpers/TypesConverter';
import * as photoService from '../../../services/photo';

export interface DispatchedEventCallback {
  (photoId: number): void;
}

export enum EventType {
  PHOTO_DELETE_FAILURE,
  PHOTO_DELETE_SUCCESS,
  PHOTO_COVER_FAILURE,
  PHOTO_COVER_SUCCESS
}

class Subscription {
  constructor(
    public eventType: EventType,
    public callback: DispatchedEventCallback
  ) {}
}

export class List {
  element: JQuery;
  groupId: number;
  private photos: Photo[];
  private subscriptions: Subscription[];

  constructor(element: JQuery, groupId: number) {
    this.subscriptions = [];
    this.groupId = groupId;
    this.element = element
      .on('click', '.button', this.handleButtonClick.bind(this));

    this.photos = element
      .children()
      .toArray()
      .map((node: HTMLElement) => this.domNodeToPhoto(node));

    this.ensureCoverPhoto();
  }

  get length(): number {
    return this.photos.length;
  }

  dispatchEvent(eventType: EventType, photoId?: number) {
    const subscription = this.subscriptions.find((subscription) => subscription.eventType === eventType);

    if (subscription) {
      subscription.callback(photoId);
    }
  }

  on(eventType: EventType, callback: DispatchedEventCallback) {
    this.subscriptions.push(new Subscription(eventType, callback));
  }

  domNodeToPhoto(node: HTMLElement): Photo {
    const element = $(node);
    const id = to.int(element.attr('data-id'));
    const thumbnail = element.find('img:first').attr('src');
    const isCover = !!to.int(element.attr('data-is-cover'));

    return new Photo(id, thumbnail, isCover, this, element);
  }

  trans(key: string): string {
    return this.element.data('translations')[key];
  }

  add(photo: Photo) {
    const element = this.element
      .append(photo.toHtmlString())
      .children()
      .last();

    photo.element = element;
    this.photos.push(photo);
  }

  ensureCoverPhoto() {
    if (this.length) {
      const hasCoverPhoto = this.photos.some((photo) => photo.isCover);

      if (!hasCoverPhoto) {
        this.setPhotoAsCover(this.photos[0].id);
      }
    }
  }

  async handleButtonClick(e: JQueryEventObject) {
    const button = $(e.currentTarget);
    const photoId = to.int(button.closest('.hoverable').attr('data-id'));

    if (button.children('.trash').length) {
      await this.destroyPhoto(photoId);

    } else if (button.children('.star').length && !button.is('.yellow')) {
      await this.setPhotoAsCover(photoId, true);
    }
  }

  async destroyPhoto(photoId: number) {
    const index = this.photos.findIndex((photo) => photo.id === photoId);

    if (index !== -1) {
      const photo = this.photos[index];

      if (photo.isDisabled) {
        return;
      }

      try {
        photo.isDisabled = true;
        await photoService.destroy(photo.id, this.groupId);
        photo.dispose();
        this.photos.splice(index, 1);
        this.ensureCoverPhoto();
        this.dispatchEvent(EventType.PHOTO_DELETE_SUCCESS, photoId);

      } catch(ex) {
        photo.isDisabled = false;
        this.dispatchEvent(EventType.PHOTO_DELETE_FAILURE, photoId);
      }
    }
  }

  async setPhotoAsCover(photoId: number = this.photos[0].id, isDispatchSuccess?: boolean) {
    const currentCoverPhoto = this.photos.find((photo) => photo.isCover);
    const photo = this.photos.find((photo) => photo.id === photoId);

    if (photo.isDisabled) {
      return;
    }

    if (currentCoverPhoto) {
      currentCoverPhoto.setAsCover(false);
    }

    photo.setAsCover();

    try {
      await photoService.setAsCover(photo.id, this.groupId);

      if (isDispatchSuccess) {
        this.dispatchEvent(EventType.PHOTO_COVER_SUCCESS, photoId);
      }

    } catch (ex) {
      photo.setAsCover(false);

      if (currentCoverPhoto) {
        currentCoverPhoto.setAsCover();
      }

      this.dispatchEvent(EventType.PHOTO_COVER_FAILURE, photoId);
    }
  }
}

export class Photo {
  id: number;
  thumbnail: string;
  isCover: boolean;
  element: JQuery;
  private list: List;

  constructor(id: number, thumbnail: string, isCover: boolean, list: List, element?: JQuery) {
    this.id = id;
    this.thumbnail = thumbnail;
    this.isCover = isCover;
    this.element = element;
    this.list = list;
  }

  set isDisabled(isDisabled: boolean) {
    this.element.toggleClass('disabled', isDisabled);
  }

  get isDisabled() {
    return this.element.hasClass('disabled');
  }

  dispose() {
    this.element
      .find('img')
      .prop('src', '');

    this.element
      .find('[data-content]')['popup']('destroy');

    this.element.remove();
    this.element = null;
    this.list = null;
  }

  setAsCover(isCover: boolean = true) {
    this.isCover = isCover;
    this.element
      .attr('data-is-cover', to.int(isCover))
      .find('.button:last')
      .toggleClass('yellow', isCover)
      .attr('data-content', this.getCoverButtonLabel());
  }

  toHtmlString() {
    return `
      <div
        class="ui left floated segment hoverable"
        style="margin-top: 0;"
        data-id="${this.id}"
        data-is-cover="${to.int(this.isCover)}"
      >
        <div style="overflow: hidden; width: 14rem; height: 9rem;">
          <img class="ui fluid image" src="${this.thumbnail}" style="width: 100%; -webkit-transform: translateY(-50%); transform: translateY(-50%); top: 50%;">
          <div class="ui icon button"><i class="trash icon"></i></div>
          <div class="ui icon button ${(this.isCover) ? 'yellow' : '' }" data-content="${this.getCoverButtonLabel()}" data-position="top center"><i class="star icon purple"></i></div>
        </div>
      </div>
    `;
  }

  private getCoverButtonLabel(): string {
    return this.list.trans((this.isCover) ? 'group/photos.is_cover_photo' : 'group/photos.set_cover_photo');
  }
}