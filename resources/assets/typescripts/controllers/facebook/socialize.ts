'use strict';

interface Params {}

interface Query {
  readonly error?: string;
}

export const socialize = (params: Params, query: Query) => {
  const hasOpener = _.has(window, 'opener.popup');
  const target = (hasOpener) ? window.opener : window;
  const path = target.location.pathname;
  let url = (path === '/socialize/facebook') ? '/login' : path;

  if (query.error) {
    url += `?error=${query.error}`;
  }

  target.location.replace(url);

  if (hasOpener) {
    target.popup.close();
  }
};
