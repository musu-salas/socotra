export namespace Str {
  export function sprintf(...args: string[]): string {
    const [ text ] = args;
    let i = 1;

    return text.replace(/%s/g, () => (i < args.length) ? args[i++] : '');
  }
}
