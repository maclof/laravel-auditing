<?php

namespace OwenIt\Auditing\Drivers;

use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\AuditDriver;

class Database implements AuditDriver
{
	/**
	 * {@inheritdoc}
	 */
	public function audit(Auditable $model): ?Audit
	{
		$implementation = Config::get('audit.implementation', \OwenIt\Auditing\Models\Audit::class);

		$attributes = $model->toAudit();

		if (empty($attributes['old_values']) && empty($attributes['new_values'])) {
			return null;
		}

		$tags = json_decode($attributes['tags'], true);
		unset($attributes['tags']);

		$model = call_user_func([$implementation, 'create'], $attributes);

		foreach ($tags as $name => $value) {
			$model->tags()->create([
				'name'  => $name,
				'value' => $value,
			]);
		}

		return $model;
	}

	/**
	 * {@inheritdoc}
	 */
	public function prune(Auditable $model): bool
	{
		if (($threshold = $model->getAuditThreshold()) > 0) {
			$forRemoval = $model->audits()
				->latest()
				->get()
				->slice($threshold)
				->pluck('id');

			if (!$forRemoval->isEmpty()) {
				return $model->audits()
					->whereIn('id', $forRemoval)
					->delete() > 0;
			}
		}

		return false;
	}
}
