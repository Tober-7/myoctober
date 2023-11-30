<?php namespace Tobiasfasanok\Arrivals\Models;

use Model;

/**
 * Arrival Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Arrival extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'tobiasfasanok_arrivals';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    public $timestamps = false;

    protected $fillable = ['user_id', 'user_name', 'date'];

    public $belongsTo = [
        'user' => 'Tobiasfasanok\Users\Models\User',
    ];
}
