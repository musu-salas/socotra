'use strict';

export const isMobile = (): boolean => {
  return (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
};
