import * as MapI from '../interfaces/Maps';

export function urlQueryToMap(queryString: string = window.location.search): MapI.ImmutableStringsMap {
  let query = (queryString.charAt(0) === '?') ? queryString.substring(1) : queryString;

  if (!query) {
    return {};
  }

  return JSON.parse(`{"${decodeURI(query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"')}"}`);
};

export function mapToUrlQuery(map: MapI.ImmutableStringsMap, separator: string = '&'): string {
  return Object.keys(map).map((key) => `${key}=${map[key]}`).join(separator);
};

export function windowFromUrl(url: string, title: string, features: MapI.StringNumbersMap = {}): Window {
  const featuresWithDefault = {
    copyhistory: 'no',
    directories: 'no',
    location: 'yes',
    menubar: 'no',
    resizable: 'no',
    scrollbars: 'yes',
    status: 'no',
    toolbar: 'no',
    ...features
  };

  return window.open(url, title, mapToUrlQuery(featuresWithDefault, ','));
};
