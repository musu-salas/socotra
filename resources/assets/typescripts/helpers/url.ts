'use strict';

import { ImmutableStringsMap, StringNumbersMap } from '../interfaces/Maps';

export const queryStringToMap = (queryString: string = window.location.search): ImmutableStringsMap => {
  let query = queryString.substring(1);

  if (!query) {
    return {};
  }

  return JSON.parse(`{"${decodeURI(query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"')}"}`);
};

export const mapToQueryString = (map: ImmutableStringsMap, separator: string = '&'): string => {
  return Object.keys(map).map((key) => `${key}=${map[key]}`).join(separator);
};

export const windowFromUrl = (url: string, title: string, features: StringNumbersMap = {}): Window => {
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
