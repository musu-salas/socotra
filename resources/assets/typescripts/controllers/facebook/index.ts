'use strict';

import { queryStringToMap } from '../../helpers/url';
import { StringsMap } from '../../interfaces/Maps';
import { socialize } from './socialize';

export default class Facebook {
  static socialize(params: StringsMap, query: string) {
    socialize(params, queryStringToMap(query));
  }
}
