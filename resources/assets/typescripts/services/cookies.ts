import * as MapI from '../interfaces/Maps';

export function getAll(): MapI.ImmutableStringsMap {
  let cookies: MapI.StringsMap = {};

	for (const cookie of document.cookie.split('; ')) {
		const [name, value] = cookie.split('=');

		cookies[name] = decodeURIComponent(value);
	}

  return cookies;
}

export function get(key: string): string {
  return getAll()[key];
}
