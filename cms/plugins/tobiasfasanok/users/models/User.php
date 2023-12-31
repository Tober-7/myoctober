<?php namespace Tobiasfasanok\Users\Models;

use Model;

/**
 * User Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class User extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'tobiasfasanok_users';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    public $timestamps = false;

    protected $fillable = ['name', 'email', 'password', 'token'];

    public $hasMany = [
        'arrivals' => 'Tobiasfasanok\Arrivals\Models\Arrival',
    ];
}
