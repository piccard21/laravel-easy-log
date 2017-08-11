<?php

namespace Piccard\LEL\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable;

    public function __construct() {
        $this->fillable = array_merge(['channel', 'level', 'message', 'app', 'server', 'created'], config('laravel-easy-log.db.columns'));
    }

    /**
     * 
     * @param Builder $query
     * @return Builder $query
     */
    public function scopeGetLogs($query) {
        // level
        $query = $query->whereIn('level', session()->get('result-level', [100, 200, 250, 300, 400, 500, 550, 600]));

        // search-text in column
        foreach (session()->get('result-search-column', ['message']) as $index => $col) {
            if ($index == 0) {
                $query = $query->where($col, 'like', '%' . session()->get('result-search-text', '') . '%');
            } else {
                $query = $query->orWhere($col, 'like', '%' . session()->get('result-search-text', '') . '%');
            }
        }

        // daterange
        if (!empty(session()->get('result-daterange-carbon', null))) {
            $query = $query->whereDate('created', '>=', session()->get('result-daterange-carbon')['startDate']);
            $query = $query->whereDate('created', '<=', session()->get('result-daterange-carbon')['endDate']);
        }

        // order by
        return $query->orderBy(session()->get('result-sort', 'id'), session()->get('result-search-text', 'desc'));
    }

}
