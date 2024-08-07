<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'extension',
        'receipt_id'
    ];

    /**
     * Связь (обратная) 1:1 между File & Receipt
     * @return BelongsTo
     */
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }

    /**
     * Сохранение файла на диск
     * @param UploadedFile $file
     * @param int $receiptId
     * @param string $folder
     * @return File
     */
    static public function saveFile(
        UploadedFile $file,
        int $receiptId,
        string $folder = ''
    ): File
    {
        // проверить существование папки и создавать её
        if($folder != 'public') {
            if(!Storage::exists($folder)) {
                Storage::makeDirectory($folder);
            }
        }

        $path = $file->storeAs(
            $folder,
            md5(now()) . "." . $file->getClientOriginalExtension()
        );

        return File::create([
            'path' => $path,
            'extension' => strtolower($file->getClientOriginalExtension()),
            'receipt_id' => $receiptId
        ]);
    }

    /**
     * Сохранение картинки
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    static function saveImage(
        UploadedFile $file,
        string $folder = 'img'
    ):string
    {
        if(!Storage::exists($folder)) {
            Storage::makeDirectory($folder);
        }

        $path = $file->storeAs(
            $folder,
            md5(now()) . "." . $file->getClientOriginalExtension()
        );
        return $path;
    }

    /**
     * Перемещение файла в новую папку
     * и создание новой папки, если её нет
     * @param string $newFolder новая папка
     * @return bool
     */
    public function moveFile(string $newFolder): bool
    {
        if(!Storage::exists($newFolder)) {
            Storage::makeDirectory($newFolder);
        }

        $nameFile = explode('/', $this->path)[1];
        $newPath = $newFolder . DIRECTORY_SEPARATOR . $nameFile;

        $isMoved = Storage::move($this->path, $newPath);

        // обновляем путь в БД
        $this->update([
            'path' => $newPath
        ]);

        return $isMoved;
    }

    /**
     * Удаление файла с диска
     * @return void
     */
    public function deleteFile(): void
    {
        // проверять права на удаление, т.к. метод может
        // вызываться откуда угодно
        if(Auth::user()->role == Config::get('constants.role.MODERATOR')) {
            // сначала удаляем файл
            Storage::delete($this->path);
            // затем удаляем запись из БД
            $this->delete();
        }
    }

    static function deleteImage(string $path):void
    {
        // проверять права на удаление, т.к. метод может
        // вызываться откуда угодно
        if(Auth::user()->role == Config::get('constants.role.MODERATOR')) {
            // сначала удаляем файл
            Storage::delete($path);
        }
    }
}
