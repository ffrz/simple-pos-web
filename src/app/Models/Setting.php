<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class Setting extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'lastmod_datetime',
        'lastmod_user_id',
    ];

    static $settings = [];
    static $is_initialized = false;

    private static function _init()
    {
        if (!static::$is_initialized) {
            $items = Setting::all();
            foreach ($items as $item) {
                static::$settings[$item->key] = $item->value;
            }
        }
    }

    public static function value($key, $default = null)
    {
        static::_init();
        return isset(static::$settings[$key]) ? static::$settings[$key] : $default;
    }

    public static function values()
    {
        static::_init();
        return static::$settings;
    }

    public static function setValue($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value],
            ['lastmod_datetime' => now()],
            ['lastmod_user_id' => Auth::user()->id],

        );
        static::$settings['key'] = $value;
    }
}
