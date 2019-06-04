<?php

namespace OwenIt\Auditing\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $table = 'audit_tags';

	/**
	 * The fillable attributes.
	 *
	 * @var array
	 */
	protected $fillable = [
		'audit_id',
		'name',
		'value',
	];
}
