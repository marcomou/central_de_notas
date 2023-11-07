export class TimestampsBase {
  public created_at!: string | null | undefined;
  public updated_at!: string | null | undefined;
  public deleted_at!: string | null | undefined;

  get createdAt() {
    return this.created_at;
  }

  set createdAt(createdAt) {
    this.created_at = createdAt;
  }

  get updatedAt() {
    return this.updated_at;
  }

  set updatedAt(updatedAt) {
    this.updated_at = updatedAt;
  }

  get deletedAt() {
    return this.deleted_at;
  }

  set deletedAt(deletedAt) {
    this.deleted_at = deletedAt;
  }
}
