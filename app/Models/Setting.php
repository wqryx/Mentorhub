<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'description',
        'group',
    ];

    /**
     * Obtener una configuración por su clave
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        return $setting ? $setting->value : $default;
    }

    /**
     * Establecer o actualizar una configuración
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $description
     * @param string $group
     * @return Setting
     */
    public static function set($key, $value, $description = null, $group = 'general')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description,
                'group' => $group,
            ]
        );
        
        return $setting;
    }

    /**
     * Obtener todas las configuraciones agrupadas
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllGrouped()
    {
        return self::all()->groupBy('group');
    }
}
