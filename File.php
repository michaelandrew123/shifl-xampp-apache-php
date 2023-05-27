<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    public static
        $NEXT_ACTION_CREATE_NEW_SIGNATURE_ENTRY = 'create_new_signature_entry_for_pdi',
        $NEXT_ACTION_APPEND_KEY_WITH_ID = 'update_key_with_id'
    ;

    public static $TYPE_PHOTO = 'photo', $TYPE_CERTIFICATE = 'certificate';

    public function fileable()
    {
        return $this->morphTo();
    }


    protected $guarded = ["id"];

//    protected $fillable = [
//        'date',
//        'title',
//        'description',
//        'src',
//        'path',
//        'mime',
//        'size',
//        'real_path',
//        'name',
//        'new_name',
//        'type',
//        'user_id',
//        'size_type',
//        'key',
//        'position',
//        'fileable_type',
//        'fileable_id',
//        'machine_id',
//    ];

    protected $appends = [
        'url',
        'blob',
    ];

    protected $casts = [
        'date' => 'date'
    ];

//    protected function blob(): Attribute
//    {
//        return Attribute::make(
//            get: fn (string $value) =>  str_replace("data:image/png;base64,data:image/png;base64,", "data:image/png;base64,", $value),
//            set: fn (string $value) =>  str_replace("data:image/png;base64,data:image/png;base64,", "data:image/png;base64,", $value),
//        );
//    }


    public function getUrlAttribute()
    {
        if($this->src) {
            return $this->src;
        }

        return 'assets/img/products/p1.jpg';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function machine()
    {

    }

    public function getBlobAttribute($value) {
        if($value) {
            if(strpos($value, 'data:image/png;base64,') > -1) {
                return $value;
            }
            else {
                return 'data:image/png;base64,' . $value;

            }
        }

        return null;
        // $blob = null;
        //
        // $this->url = "http://127.0.0.1:8000/storage/machines/4_t1fGw_isia.png";
        // $blob = base64_encode(file_get_contents($this->url));


        // if(strpos($this->mime, 'png') > -1) {
        //     $blob = "data:image/png;base64,".base64_encode(file_get_contents($this->url));
        // }
        // else if(strpos($this->mime, 'jpg') > -1) {
        //     $blob = "data:image/png;base64,".base64_encode(file_get_contents($this->url));
        // }
        // else if(strpos($this->mime, 'jpeg') > -1) {
        //     $blob = "data:image/png;base64,".base64_encode(file_get_contents($this->url));
        // }
        // dd($blob);
        //
        // return $blob;
    }
}
