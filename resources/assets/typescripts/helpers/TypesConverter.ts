'use strict';

export default class TypesConverter {
  static string(value: string | number): string {
    return value + '';
  }
};
