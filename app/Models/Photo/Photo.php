<?php

namespace App\Models\Photo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'photos';

    /**
     * The database primary key value.
     * @var string
     */
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * Attributes that should be mass-assignable.
     * @var array
     */
    protected $fillable = ['photo'];

    public static function encodedImageUpload($image, $name, $directory = 'assets/img')
    {
        $exploded = explode(',', $image);
        $extension = preg_split("/[\/;]/", $exploded[0])[1];
        $file = base64_decode($exploded[1]);

        if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
            $filename = time() . '_' . str_slug($name, '_') . '.' .$extension;
            $path = public_path(). '/' . $directory . '/' . $filename;
            file_put_contents($path, $file);
            $photo = self::create(['photo' => $filename]);
            return $photo->id;
        } else {
            return false;
        }
    }

}
