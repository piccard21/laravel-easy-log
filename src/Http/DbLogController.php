<?php

namespace Piccard\LEL\Http;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Piccard\LEL\Http\Controller;
use Piccard\LEL\LEL;

class DbLogController extends Controller {

	/**
	 * show default Log-entries & also reset filters
	 *
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function index(Request $request) {
		// forget  filter-data
		foreach (['result-sort', 'result-sort-direction', 'result-number', 'result-level', 'result-search-text', 'result-search-column', 'result-daterange', 'result-daterange-carbon'] as $key) {
			$request->session()->forget($key);
		}
		$request->session()->regenerate();

		return view('lel::index')->with($this->getViewParameters());
	}

	/**
	 * store filter-values into session
	 *
	 * @param Request $request
	 * @return View
	 */
	public function filter(Request $request) {

		$this->validate($request, [
			'result-sort' => 'required|max:255',
			'result-sort-direction' => 'required|in:asc,desc',
			'result-number' => 'required|integer',
			'result-level.*' => 'required|integer',
			'result-search-text' => 'nullable|string|max:1000',
			'result-search-column.*' => 'required|string',
			'result-daterange' => 'nullable|string|max:100',
		]);

		session()->put('result-sort', $request->input('result-sort'));
		session()->put('result-sort-direction', $request->input('result-sort-direction'));
		session()->put('result-number', $request->input('result-number'));
		session()->put('result-level', $request->input('result-level'));
		session()->put('result-search-text', $request->input('result-search-text'));
		session()->put('result-search-column', $request->input('result-search-column'));
		session()->put('result-daterange', $request->input('result-daterange'));

		if ($request->input('result-daterange')) {
			$dates = explode('-', $request->input('result-daterange'));
			$startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]));
			$endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]));
			session()->put('result-daterange-carbon', [
				'startDate' => $startDate,
				'endDate' => $endDate,
			]);
		}

		return redirect()->route('logs-filtered');
	}

	/**
	 * get filtered logs
	 *
	 * @param Request $request
	 * @return type
	 */
	public function getFilteredLogs(Request $request) {
		return view('lel::index')->with($this->getViewParameters());
	}

	/**
	 * get the filter-values
	 *
	 * @return array filter-values
	 */
	protected function getViewParameters() {

		return [
			'paginate' => $this->getLog()->paginate(session()->get('result-number', config('laravel-easy-log.db.view.result-number'))),
			'columns' => array_merge(['id', 'channel', 'level', 'message', 'app', 'server', 'created'], config('laravel-easy-log.db.columns')),
			'resultSort' => session()->get('result-sort', 'id'),
			'resultSort-direction' => session()->get('result-sort-direction', 'desc'),
			'resultNumber' => session()->get('result-number', config('laravel-easy-log.db.view.result-number')),
			'resultLevel' => session()->get('result-level', [100, 200, 250, 300, 400, 500, 550, 600]),
			'resultSearchText' => session()->get('result-search-text', null),
			'resultSearchColumn' => session()->get('result-search-column', ['message']),
			'resultDaterange' => session()->get('result-daterange', null),
		];
	}

	/**
	 * returns the query for the filter
	 *
	 * @return Builder
	 */
	protected function getLog() {
		$query = LEL::getTable()->whereIn('level', session()->get('result-level', [100, 200, 250, 300, 400, 500, 550, 600]));

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
