'use strict';

/** @desc semantic-ui custom template errors rendering. */
export const renderTemplateErrors = (errors: StringsMap) => {
  const errorsEl = $('form .error');
  const errorsListEl = errorsEl.find('ul');
  const errorsStr = $.map(errors, (error) => `<li>${error}</li>`).join('');

  errorsListEl.html(errorsStr);

  return errorsEl.html();
};
