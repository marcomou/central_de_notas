import * as _ from 'lodash';

import { Deserializable, Serializable } from 'src/app/models/common/serialization';

export abstract class BaseModel implements Serializable, Deserializable {

  public guid!: string;
  get id(): string { return this.guid; }
  set id(value) {
    this.guid = value;
  }

  get exists(): boolean { return !!this.id; }
  get title(): string { return this.guid; } // override this please

  public created_at?: any;
  public updated_at?: any;
  public deleted_at?: any;
  get wasEverUpdated(): boolean { return this.updated_at && this.updated_at > this.created_at; }
  get isDeleted(): boolean { return !!this.deleted_at; }

  // Alternatively, you can parse the "created_at" field at the constructor with this.deserializeDateTime
  get createdAtDate(): Date|null {
    if (this.created_at instanceof Date) return this.created_at;
    if ('string' !== typeof this.created_at) return null;
    return new Date(this.created_at.replace(/-/g, "/"));
  }

  // Alternatively, you can parse the "updated_at" field at the constructor with this.deserializeDateTime
  get updatedAtDate(): Date|null {
    if (this.updated_at instanceof Date) return this.updated_at;
    if ('string' !== typeof this.updated_at) return null;
    return new Date(this.updated_at.replace(/-/g, "/"));
  }

  // Alternatively, you can parse the "deleted_at" field at the constructor with this.deserializeDateTime
  get deletedAtDate(): Date|null {
    if (this.deleted_at instanceof Date) return this.deleted_at;
    if ('string' !== typeof this.deleted_at) return null;
    return new Date(this.deleted_at.replace(/-/g, "/"));
  }

  // Deserializable

  public deserialize(input: object) {
    Object.assign(this, input);
    return this;
  }

  protected deserializeDateTime(value: string|Date|null): Date|null {
    if (value instanceof Date) {
      return value;
    }

    if (typeof value === 'string' && value.length) {
      if (value.match(/^\d{4}-\d{2}-\d{2}$/)) {
        value = value + 'T12:00:00+00:00';
      }
      else {
        if (value.match(/^\d{4}-\d{2}-\d{2} \d\d:\d\d:\d\d/)) {
          value = value.replace(' ', 'T');
        }
        if (value.match(/^\d{4}-\d{2}-\d{2}T\d\d:\d\d:\d\d$/)) {
          value = value + '+00:00';
        }
      }

      return new Date(value);
    }

    return null;
  }

  protected deserializeModel<T extends BaseModel>(
    value: object|null|undefined,
    m_constructor: {new(): T}|null = null,
    default_value: T|null = null
  ): T|null
  {
    if (null != value && (typeof value) === 'object') {
      if (m_constructor instanceof Function) {
        return (new m_constructor).deserialize(value);
      }
      console.warn("model not defined on deserialization");
    }
    return default_value;
  }

  protected setOnceProperties(): Array<string> {
    return [];
  }
  public modifiableProperties(): Array<string> {
    return [];
  }
  protected serializableProperties(purpose: string|null = null): Array<string> {
    switch(purpose) {
      case 'updating':
        return [].concat(this.modifiableProperties() as Array<never>);

      case 'putting':
        return ['guid'].concat(this.modifiableProperties());

      case 'creating':
      default:
        return [].concat(this.modifiableProperties() as Array<never>, this.setOnceProperties() as Array<never>);
    }
  }

  // Serializable

  public serialize(
    purpose: string|null = null,
    configs: {
      [k: string]: {nk?: string, pf?: Function, np?: Function}
    }|null = null
  ): object {

    return this.serializableProperties(purpose).reduce((serialized: { [k: string]: any }, key) => {
      let value = null;

      value = this[key as keyof BaseModel];

      let cfg = configs && (typeof configs === 'object') && configs[key] || null;

      if (value && (typeof value === 'object')) {
        if (value.serialize instanceof Function) {
          let objPurpose = cfg && (cfg.np instanceof Function) ? cfg.np(purpose, value) : purpose;
          value = value.serialize(objPurpose);
        }
        else if (Array.isArray(value)) {
          value = value.map(item => {
            if (item && (typeof item === 'object') && (item.serialize instanceof Function)) {
              let itPurpose = cfg && (cfg.np instanceof Function) ? cfg.np(purpose, item) : purpose;
              return item.serialize(itPurpose);
            }
            return item;
          });
        }
      }

      if (cfg && cfg.pf instanceof Function) {
        value = cfg.pf(value, key, this, serialized);
      }

      let newKey = cfg && cfg.nk || key;

      serialized[newKey] = value;

      return serialized;
    }, {});
  }

  postSerializationConfigs(): { [k: string]: any } {
    return {};
  }

  postSerializationKey(originalKey: string) {
    let config = this.postSerializationConfigs()[originalKey];
    return (config && config.nk) || originalKey;
  }

  postSerializationValue(originalKey: string) {
    let serialized: { [k: string]: any } = this.serialize('updating');
    return serialized[this.postSerializationKey(originalKey)];
  }

  attributeReplacementPatch(key: string): PatchObject {

    let value = this.postSerializationValue(key);
    let path = this.postSerializationKey(key);
    return PatchObject.replacement(this.id, path, value);
  }

  attributeRemovalPatch(key: string): PatchObject {
    let path = this.postSerializationKey(key);
    return PatchObject.removal(this.id, path);
  }

  public serializedPropertyName(originalPropertyName: string): string {
    return originalPropertyName;
  }

  protected static _changePurposeToCreateWhenPuttingNew(original_purpose: string, value: { exists?: boolean }) {
    return (original_purpose === 'putting' && !value.exists) ? 'creating' : original_purpose;
  };
}

export class PatchObject implements Serializable {

  constructor(
    public readonly id: string,
    private readonly _op: string,
    private readonly _path: string,
    private readonly _value?: any) {}

  public static removal(id: string, path: string) {
    return new PatchObject(id, 'remove', path);
  }

  public static replacement(id: string, path: string, value: any) {
    return new PatchObject(id, 'replace', path, value);
  }

  public serialize() {
    let serialized: { op: string, path: string, value?: string } = {'op': this._op, 'path': this._path};
    if (serialized['op'] === 'replace') {
      serialized.value = this._value;
    }
    return serialized;
  }
  postSerializationConfigs(): {[k: string]: {nk?: string, pf?: Function, np?: Function}} {
    return {};
  }
  postSerializationKey(originalKey: string): string {
    return '';
  }
  postSerializationValue(originalKey: string): any {
    return null;
  }
}
