export function string(value: string | number): string {
  return value + '';
}

export function int(value: string | number | boolean): number {
  return (typeof value === 'boolean') ? +value : parseInt(<string> value, 10);
}
