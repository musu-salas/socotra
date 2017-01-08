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
        prompt: 'Title shan\'t exceed 50 characters.'
      }/* TODO: Requires semantic 2.0+, {
        type: 'regExp[/[A-Z]{5,}/]',
        prompt: 'Invalid class title – words written with capital letters are allowed only in abbreviations.'
      }*/]
    },
    uvp: {
      identifier: 'uvp',
      rules: [{
        type: 'maxLength[140]',
        prompt: 'Unique value proposition shan\'t exceed 140 characters.'
      }]
    },
    for_who: {
      identifier: 'for_who',
      rules: [{
        type: 'maxLength[255]',
        prompt: 'For whom your class is description shan\'t exceed 255 characters.'
      }]
    }
  });


}(jQuery));
