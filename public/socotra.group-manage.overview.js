(function($) {
  'use strict';


  $('form').form({
    creativeField1: {
      identifier: 'creative_field1',
      rules: [{
        type: 'empty'
      }]
    },
    title: {
      identifier: 'title',
      rules: [{
        type: 'empty'
      }, {
        type: 'maxLength[50]',
        prompt: __('validation.max.string', { attribute: 'title', max: 50 })
      }/* TODO: Requires semantic 2.0+, {
        type: 'regExp[/[A-Z]{5,}/]',
        prompt: 'Invalid class title – words written with capital letters are allowed only in abbreviations.'
      }*/]
    },
    uvp: {
      identifier: 'uvp',
      rules: [{
        type: 'maxLength[140]',
        prompt: __('validation.max.string', { attribute: 'unique value proposition', max: 140 })
      }]
    },
    for_who: {
      identifier: 'for_who',
      rules: [{
        type: 'maxLength[255]',
        prompt: __('validation.max.string', { attribute: 'for whom your class is description', max: 255 })
      }]
    }
  });


}(jQuery));
