'use strict';

import * as MapI from '../interfaces/Maps';

/** @desc semantic-ui custom template errors rendering. */
export const renderTemplateErrors = (errors: MapI.StringsMap) => {
  const errorsEl = $('form .error');
  const errorsListEl = errorsEl.find('ul');
  const errorsStr = $.map(errors, (error) => `<li>${error}</li>`).join('');

  errorsListEl.html(errorsStr);

  return errorsEl.html();
};
