<?php

namespace OwenIt\Auditing\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model implements \OwenIt\Auditing\Contracts\Audit
{
	use \OwenIt\Auditing\Audit;

	/**
	 * {@inheritdoc}
	 */
	protected $guarded = [];

	/**
	 * {@inheritdoc}
	 */
	protected $casts = [
		'old_values'   => 'json',
		'new_values'   => 'json',
		'auditable_id' => 'integer',
	];

	/**
	 * The tags relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function tags()
	{
		return $this->hasMany('OwenIt\Auditing\Models\Tag', 'audit_id');
	}
}
