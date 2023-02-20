<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class Userapi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'userId',
        'title',
        'completed'
    ];

    public static function addNewRow($value) {
        try {
            DB::table("userapis")->insert([
                'id' => $value->id,
                'userId' => $value->userId,
                'title' => $value->title,
                'completed' => $value->completed
            ]);
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getData($value) {
        try {
            if (!is_null($value->page) && !is_null($value->per_page)) {
                //$countRows = DB::table("userapis")->count();
                //$limit = $countRows/$value->per_page;
                $init = (($value->per_page* $value->page) - $value->per_page);
                return DB::table("userapis")->groupBy('id')->offset($init)->limit($value->per_page)->get();
            }
            if (!is_null($value->search)) {
                return DB::table("userapis")->where('title', 'like', "%$value->search%")->get();
            }
            if (!is_null($value->user_id)) {
                return DB::table("userapis")->where('userId', $value->user_id)->get();
            }
            if (!is_null($value->completed)) {
                $boolTemp = 0;
                switch($value->completed) {
                    case "true":
                        $boolTemp = 1;
                        break;
                }
                return DB::table("userapis")->where('completed', $boolTemp)->get();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return null;
    }
}
