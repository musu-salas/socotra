'use strict';

const STICKY_FIXED_OFFSET = 40;
const stickyEl = $('#value-proposition');
const featuresEl = $('#features');
let hasSticky = false;
const styckyOffsets = {
  min: 0,
  max: 0
};

function setSticky() {
  hasSticky = !stickyEl.parent().next().children('img').first().is(':hidden');
}

function setStickyOffsets() {
  styckyOffsets.min = stickyEl.offset().top - STICKY_FIXED_OFFSET;
  styckyOffsets.max = featuresEl.offset().top - stickyEl.height() - STICKY_FIXED_OFFSET * 2;
}

function setStickyState(state: 1 | 2 | 3) {
  stickyEl
    .removeAttr('style');

  switch (state) {
    case 1:
      stickyEl
        .removeClass('sticky');
    break;

    case 2:
      stickyEl
        .addClass('sticky')
        .css({
          width: stickyEl.parent().width(),
          top: STICKY_FIXED_OFFSET
        });
      break;

    case 3:
      stickyEl
        .addClass('sticky')
        .css({
          top: 'auto',
          bottom: STICKY_FIXED_OFFSET,
          position: 'absolute',
          width: stickyEl.parent().width()
        });
      break;
  }
}

$(window)
  .on('resize', () => {
    setStickyOffsets();
    setSticky();
  })
  .on('scroll', (e) => {
    if (!hasSticky) {
      return;
    }

    const pageScrollTop = $(e.target).scrollTop();

    if (pageScrollTop > styckyOffsets.max) {
      setStickyState(3);

    } else if (pageScrollTop > styckyOffsets.min) {
      setStickyState(2);

    } else {
      setStickyState(1);
    }
  });

setStickyOffsets();
setSticky();
