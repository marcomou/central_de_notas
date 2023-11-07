export default function getValueFromObjectByArrayPath(item: any, paths: string[]) {
  return paths.reduce((previus, path) => {
    return previus[path]
  }, item);
}
