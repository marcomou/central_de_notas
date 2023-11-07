export interface Deserializable {
  deserialize(input: object): Deserializable;
}

export interface Serializable {
  serialize(purpose: string): object;
}
