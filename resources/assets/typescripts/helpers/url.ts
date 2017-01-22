import * as MapInts from '../interfaces/Maps';

export function queryStringToMap(queryString: string = window.location.search): MapInts.ImmutableStringsMap {
  let query = queryString.substring(1);

  if (!query) {
    return {};
  }

  return JSON.parse(`{"${decodeURI(query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"')}"}`);
};

export function mapToQueryString(map: MapInts.ImmutableStringsMap, separator: string = '&'): string {
  return Object.keys(map).map((key) => `${key}=${map[key]}`).join(separator);
};

export function windowFromUrl(url: string, title: string, features: MapInts.StringNumbersMap = {}): Window {
  const featuresWithDefault = _.assign({
    copyhistory: 'no',
    directories: 'no',
    location: 'yes',
    menubar: 'no',
    resizable: 'no',
    scrollbars: 'yes',
    status: 'no',
    toolbar: 'no'

  }, features);

  return window.open(url, title, mapToQueryString(featuresWithDefault, ','));
};
