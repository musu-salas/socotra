'use strict';

import { renderTemplateErrors } from '../../helpers/form';

interface Params {}

interface Query {}

export const password = (params: Params, query: Query) => {
  $('form').form({
    email: {
      identifier: 'email',
      rules: [{
        prompt : 'Please fix your email address.',
        type: 'email'
      }]
    },
    password: {
      identifier: 'password',
      rules: [{
        prompt : 'Password must be at least 6 characters.',
        type: 'length[6]'
      }]
    },
    password_confirmation: {
      identifier: 'password_confirmation',
      rules: [{
        prompt : 'Password and its confirmation doesn\'t match.',
        type: 'match[password]'
      }]
    }
  }, {
    templates: {
      error: renderTemplateErrors
    }
  });
};
