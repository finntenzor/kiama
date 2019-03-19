function getEnd(string, separator = '\\') {
  const list = string.split(separator)
  return list[list.length - 1]
}

export function last(val) {
  return getEnd(val)
}
