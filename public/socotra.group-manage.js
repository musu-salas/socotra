(function() {
  'use strict';


  var columns = $('#tips-column, #steps-column');


  function setColumnsMinHeight() {
    var width = $(this).width();

    if (width > 767) {
      columns.css('min-height', $(document).height() - 80);

    } else {
      columns.css('min-height', 0);
    }
  }


  $(window).on('resize', setColumnsMinHeight).trigger('resize');


}(jQuery));
