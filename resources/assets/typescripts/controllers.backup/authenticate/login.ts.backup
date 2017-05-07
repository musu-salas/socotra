'use strict';

import { renderTemplateErrors } from '../../helpers/form';
import { windowFromUrl } from '../../helpers/url';
import { isMobile } from '../../helpers/userAgent';

interface Params {}

interface Query {}

const handleFacebookClick = (e: Event) => {
  const WIDTH = 530;
  const HEIGHT = 500;
  const winEl = $(window);
  const buttonEl = e.currentTarget as HTMLElement;
  const href = buttonEl.getAttribute('href') || '';
  const title = buttonEl.getAttribute('title') || '';
  const left = winEl.width() / 2 - WIDTH / 2;
  const top = winEl.height() / 2 - HEIGHT / 2;

  window.popup = windowFromUrl(href, title, {
    height: HEIGHT,
    left,
    top,
    width: WIDTH
  });

  e.preventDefault();
  return false;
};

export const login = (params: Params, query: Query) => {
  if (!isMobile()) {
    $('a[href*="/socialize/facebook"]').on('click', handleFacebookClick);
  }

  $('form').form({
    email: {
      identifier: 'email',
      rules: [{
        prompt: 'Please fix your email address.',
        type: 'email'
      }]
    },
    password: {
      identifier: 'password',
      rules: [{
        prompt: 'Password must be at least 6 characters.',
        type: 'length[6]'
      }]
    }
  }, {
    templates: {
      error: renderTemplateErrors
    }
  });
};
