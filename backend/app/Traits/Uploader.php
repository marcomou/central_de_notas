<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Upload files
 */
trait Uploader
{
    /**
     * Array de arquivos antigos
     *
     * @var array
     */
    public $oldFiles = [];

    /**
     * Set the path to save files
     *
     * @return string
     */
    protected abstract function path(): string;

    /**
     * @return void
     */
    public static function bootUploader()
    {
        static::updating(function (Model $model) {

            /** Todos os campos que foram modificados */
            $fieldsUpdated = array_keys($model->getDirty());

            /** Todos os campos de arquivos que foram modificados*/
            $filefieldsUpdated = array_intersect($fieldsUpdated, self::$fileFields);

            /** Filtrar os campos de arquivos que foram atualizados e que tenham algum nome válido */
            $fileFieldsToBeUpdated = Arr::where($filefieldsUpdated, function ($fileField) use ($model) {
                return $model->getOriginal($fileField);
            });

            $model->oldFiles = array_map(function ($fileField) use ($model) {
                return $model->getOriginal($fileField);
            }, $fileFieldsToBeUpdated);
        });
    }

    /**
     * Upload a unique file
     *
     * @param UploadedFile $file
     *
     * @return void
     */
    public function uploadFile(UploadedFile $file)
    {
        $file->store($this->path());
    }

    /**
     * Upload a multiples files
     *
     * @param UploadedFile[] $files
     *
     * @return void
     */
    public function uploadFiles(array $files)
    {
        foreach ($files as $file) {
            $this->uploadFile($file);
        }
    }

    /**
     * Remove a unique file
     *
     * @param UploadedFile|string $file
     *
     * @return void
     */
    public function removeFile($file)
    {
        $fileName = $file instanceof UploadedFile ? $file->hashName() : $file;

        Storage::delete("{$this->path()}/{$fileName}");
    }

    /**
     * Remove a multiples files
     *
     * @param UploadedFile[] $files
     *
     * @return void
     */
    public function removeFiles(array $files)
    {
        foreach ($files as $file) {
            $this->removeFile($file);
        }
    }

    /**
     * Exclui arquivos antigos que foram atualizados
     *
     * @param UploadedFile[] $data
     *
     * @return void
     */
    public function removeOldFiles()
    {
        $this->removeFiles($this->oldFiles);
    }

    /**
     * Retorna o link do arquivo
     *
     * @param  UploadedFile|string $file
     *
     * @return string
     */
    public function getUrl($file)
    {
        $path = $this->getRelativePath($file);

        if (Storage::exists($path)) {
            if (config('filesystems.default') === 's3' && Storage::getVisibility($path) === 'private')
                return Storage::temporaryUrl($path, now()->addMinutes(5));

            return Storage::url($path);
        }

        return null;
    }

    /**
     * Retorna o caminho relativo do arquivo
     *
     * @params  UploadedFile|string $file
     *
     * @return string
     */
    public function getRelativePath($file)
    {
        if ($file instanceof UploadedFile) {
            $file = $file->hashName();
        }

        return "{$this->path()}/$file";
    }

    /**
     * Extract attributes files to upload
     *
     * @param array $attributes
     * @var array $uploadedFiles
     * @return array
     */
    public static function extractFiles(array &$attributes = [])
    {
        $uploadedFiles = [];

        foreach (self::$fileFields as $fileField) {

            if (isset($attributes[$fileField]) && $attributes[$fileField] instanceof UploadedFile) {
                $uploadedFiles[] = $attributes[$fileField];

                $attributes[$fileField] = $attributes[$fileField]->hashName();
            }
        }

        return $uploadedFiles;
    }

    /**
     * Cria um novo vídeo
     *
     * @param array $attributes
     * @return $this
     * @throws \Throwable
     */
    public static function create(array $attributes = [])
    {
        $files = self::extractFiles($attributes);
        $attributes['file_name'] = $files[0]->getClientOriginalName();

        try {
            DB::beginTransaction();

            $model = static::query()->create($attributes);

            $model->uploadFiles($files);

            DB::commit();

            return $model;
        } catch (\Throwable $th) {
            if (isset($model)) {
                $model->removeFiles($files);
            }
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Atualiza informações do model
     *
     * @param array $attributes
     * @param array $options
     * @return void
     */
    public function update(array $attributes = [], array $options = [])
    {
        $files = self::extractFiles($attributes);
        if($files){
            $attributes['file_name'] = $files[0]->getClientOriginalName();
        }
        try {
            DB::beginTransaction();

            $saved = parent::update($attributes, $options);

            if ($saved) {
                $this->uploadFiles($files);
            }

            DB::commit();

            if ($saved && count($files)) {
                $this->removeOldFiles();
            }

            return $saved;
        } catch (\Throwable $th) {
            $this->removeFiles($files);
            DB::rollBack();
            throw $th;
        }
    }
}
