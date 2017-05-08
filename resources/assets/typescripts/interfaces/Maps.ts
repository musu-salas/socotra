export interface ImmutableStringsMap {
  readonly[property: string]: string;
}

export interface StringsMap {
  [property: string]: string;
}

export interface StringNumbersMap {
  [property: string]: string | number;
}

export interface FilesMap {
  [property: string]: File;
}