interface Hooks {
  before: (done: (isResolve?: boolean) => void) => void;
  after: () => void;
}

interface StringsMap {
  [property: string]: string;
}

interface RouteController {
  (params?: StringsMap, query?: string, hooks?: Hooks): void;
}

interface RoutesMap {
  [route: string]: RouteController | NamedRoute;
}

interface ParamsMap {
  [property: string]: string | number | boolean;
}

interface NamedRoute {
  as?: string;
  uses: RouteController;
  hooks?: Hooks;
}

declare class Navigo {
  root: string;

  constructor(r?: string | null, useHash?: boolean);

  navigate(path?: string, absolute?: boolean): Navigo;

  // .on(() => {}); show home page here
  on(controller: RouteController): Navigo;

  // .on('/products/list', () => {}, {}); display all the products
  // .on('/trip/:tripId/edit', {
  //   as: 'trip.edit',
  //   uses: () => {},
  //   hooks: {}
  // });
  on(route: string, controller: RouteController | NamedRoute, hooks?: Hooks): Navigo;

  // .on({
  //   '/products/list': () => {},
  //   '/products': () => {},
  //   ...
  // });
  // .on({
  //   '/trip/:tripId/edit': {
  //     as: 'trip.edit',
  //     uses: () => {},
  //     hooks: {}
  //   }
  //   ...
  // });
  on(routes: RoutesMap): Navigo;

  notFound(handler: (query: StringsMap) => void): Navigo;

  resolve(current?: string): boolean;

  destroy(): void;

  updatePageLinks(): void;

  generate(name: string, params?: ParamsMap): string;

  link(path: string): string;

  pause(isPaused: boolean): void;

  disableIfAPINotAvailable(): void;
}

declare module 'navigo' {
  export default Navigo;
}
