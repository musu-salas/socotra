import * as MapI from '../interfaces/Maps';
import * as UrlHelper from './url';

interface Resolver {
  (params: MapI.ImmutableStringsMap, query: MapI.ImmutableStringsMap): void;
}

class Route {
  readonly pathParts: string[];
  readonly resolver: Resolver;

  constructor(path: string, resolver: Resolver) {
    this.pathParts = path.replace(/^\//, '').split('/');
    this.resolver = resolver;
  }

  hasEqualPathParts(pathPartsToResolve: string[]): boolean {
    if (this.pathParts.length !== pathPartsToResolve.length) {
      return false;
    }

    return this.pathParts.every((part, i) => part === pathPartsToResolve[i]);
  }
}

export default class UrlRouteParser {
  routes: Route[];
  params: MapI.StringsMap;

  constructor() {
    this.routes = [];
    this.params = {};
  }

  private extractLocale(pathPartsToResolve: string[]): string[] {
    const [ locale ] = pathPartsToResolve;

    if (locale === document.documentElement.lang) {
      this.params['locale'] = locale;

      return pathPartsToResolve.slice(1);
    }

    return pathPartsToResolve;
  }

  private extractParams(routePathParts: string[], pathPartsToResolve: string[]): boolean {
    const routePathPartsLength = routePathParts.length;
    const pathPartsToResolveWithoutLocale = this.extractLocale(pathPartsToResolve);

    if (routePathPartsLength < pathPartsToResolveWithoutLocale.length) {
      return true;
    }

    for (let i = 0; i < routePathPartsLength; i++) {
      const routePart = routePathParts[i];
      const partToResolve = pathPartsToResolveWithoutLocale[i];

      if (partToResolve) {
        if (routePart.charAt(0) === ':') {
          this.params[routePart.substr(1)] = partToResolve;
          continue;
        }

        if (routePart !== partToResolve) {
          return false;
        }

      } else if (routePart.charAt(0) !== ':') {
        return false;
      }
    }

    return true;
  }

  add(path: string, resolver: Resolver) {
    const route = new Route(path, resolver);

    this.routes.push(route);
  }

  resolve(): boolean {
    const origin = window.location.origin;
    const href = window.location.href;
    const url = href
      .substr(origin.length)
      .replace(/\/+/g, '/')
      .replace(/^\/|\/($|\?)/, '')
      .replace(/#.*$/, '');

    const [ path, query ] = url.split('?', 2);
    const pathParts = path.split('/');

    for (const route of this.routes) {
      if (route.hasEqualPathParts(pathParts)) {
        route.resolver({}, UrlHelper.urlQueryToMap(query));
        return true;
      }

      if (this.extractParams(route.pathParts, pathParts)) {
        route.resolver(this.params, UrlHelper.urlQueryToMap(query));
        return true;
      }
    }

    return false;
  }
}
