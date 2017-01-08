'use strict';

import { queryStringToMap } from '../../helpers/url';
import { StringsMap } from '../../interfaces/Maps';
import { login } from './login';
import { password } from './password';

export default class Authenticate {
  static login(params: StringsMap, query: string) {
    login(params, queryStringToMap(query));
  }

  static password(params: StringsMap, query: string) {
    password(params, queryStringToMap(query));
  }
}
