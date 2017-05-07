'use strict';

import UrlRouteParser from '../../helpers/UrlRouteParser';

import * as photos from './photos';

const router = new UrlRouteParser();

router.add('home/classes/:group/photos', photos.init);

router.resolve();
