import * as cookies from './cookies';

export function destroy(photoId: number, groupId: number): Promise<string> {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: `/api/v1/classes/${groupId}/photos/${photoId}`,
      method: 'DELETE',
      headers: {
        'X-XSRF-TOKEN': cookies.get('XSRF-TOKEN')
      },
      cache: false,
      success: resolve,
      error: () => reject(new Error('Generic error when deleting photo.'))
    });
  });
}

export function setAsCover(photoId: number, groupId: number): Promise<string> {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: `/api/v1/classes/${groupId}/cover_photo`,
      method: 'PUT',
      headers: {
        'X-XSRF-TOKEN': cookies.get('XSRF-TOKEN')
      },
      data: {
        photo_id: photoId
      },
      success: resolve,
      error: () => reject(new Error('Generic error when setting photo as cover.'))
    });
  });
}
