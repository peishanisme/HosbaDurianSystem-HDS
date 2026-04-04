<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HarvestRecord;
use App\Models\HarvestEvent;
use Carbon\Carbon;

class HarvestController extends Controller
{
	/**
	 * Return spoilt and not-spoilt totals for a date range.
	 * Query params: from, to, date (single)
	 */
	public function summary(Request $request)
	{
		$date = $request->input('date');
		$from = $request->input('from');
		$to = $request->input('to');

		if ($date) {
			$from = $to = $date;
		} else {
			$from = $from ?? now()->subDays(7)->toDateString();
			$to = $to ?? now()->toDateString();
		}

		$totals = HarvestRecord::selectRaw(<<<SQL
			SUM(CASE WHEN spoilt THEN weight ELSE 0 END) as spoilt_weight,
			SUM(CASE WHEN NOT spoilt THEN weight ELSE 0 END) as not_spoilt_weight,
			SUM(CASE WHEN spoilt THEN num_of_fruits ELSE 0 END) as spoilt_fruits,
			SUM(CASE WHEN NOT spoilt THEN num_of_fruits ELSE 0 END) as not_spoilt_fruits
		SQL
		)
			->whereBetween('harvest_date', [$from, $to])
			->first();
		$responseData = [
			'totals' => $totals,
		];

		if ($date) {
			$responseData['date'] = $date;
		} else {
			$responseData['from'] = $from;
			$responseData['to'] = $to;
		}

		return response()->json([
			'success' => true,
			'data' => $responseData,
		]);
	}

	/**
	 * Day summary: totals for a single date (defaults to today).
	 * Query param: date (YYYY-MM-DD)
	 */
	public function daySummary(Request $request)
	{
		// Require client to provide a date (YYYY-MM-DD)
		if (! $request->has('date')) {
			return response()->json([
				'success' => false,
				'message' => 'Please provide a date (YYYY-MM-DD) as the "date" query parameter.'
			], 422);
		}

		try {
			$date = Carbon::parse($request->input('date'))->toDateString();
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Invalid date format. Use YYYY-MM-DD.'
			], 422);
		}

		$totals = HarvestRecord::selectRaw(<<<SQL
			SUM(CASE WHEN spoilt THEN weight ELSE 0 END) as spoilt_weight,
			SUM(CASE WHEN NOT spoilt THEN weight ELSE 0 END) as not_spoilt_weight,
			SUM(CASE WHEN spoilt THEN num_of_fruits ELSE 0 END) as spoilt_fruits,
			SUM(CASE WHEN NOT spoilt THEN num_of_fruits ELSE 0 END) as not_spoilt_fruits
		SQL
		)
			->whereDate('harvest_date', $date)
			->first();

		$details = HarvestRecord::leftJoin('trees', 'harvest_records.tree_uuid', '=', 'trees.uuid')
			->selectRaw(<<<SQL
				harvest_records.tree_uuid as tree_uuid,
				trees.tree_tag as tree_tag,
				SUM(CASE WHEN spoilt THEN num_of_fruits ELSE 0 END) as spoilt_fruits,
				SUM(CASE WHEN NOT spoilt THEN num_of_fruits ELSE 0 END) as not_spoilt_fruits,
				SUM(CASE WHEN spoilt THEN weight ELSE 0 END) as spoilt_weight,
				SUM(CASE WHEN NOT spoilt THEN weight ELSE 0 END) as not_spoilt_weight
			SQL
			)
			->whereDate('harvest_date', $date)
			->groupBy('harvest_records.tree_uuid', 'trees.tree_tag')
			->orderBy('trees.tree_tag')
			->get();

		return response()->json([
			'success' => true,
			'data' => [
				'date' => $date,
				'totals' => $totals,
				'details' => $details,
			],
		]);
	}

	/**
	 * Week summary: totals for the week containing the provided date (defaults to current week).
	 * Query param: date (YYYY-MM-DD)
	 */
	public function weekSummary(Request $request)
	{
		// Require client to provide a date range via `from` and `to` (YYYY-MM-DD)
		$from = $request->input('from');
		$to = $request->input('to');

		if (! $from || ! $to) {
			return response()->json([
				'success' => false,
				'message' => 'Please provide both from and to dates (YYYY-MM-DD).'
			], 422);
		}

		try {
			$from = Carbon::parse($from)->toDateString();
			$to = Carbon::parse($to)->toDateString();
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Invalid date format. Use YYYY-MM-DD.'
			], 422);
		}

		$totals = HarvestRecord::selectRaw(<<<SQL
			SUM(CASE WHEN spoilt THEN weight ELSE 0 END) as spoilt_weight,
			SUM(CASE WHEN NOT spoilt THEN weight ELSE 0 END) as not_spoilt_weight,
			SUM(CASE WHEN spoilt THEN num_of_fruits ELSE 0 END) as spoilt_fruits,
			SUM(CASE WHEN NOT spoilt THEN num_of_fruits ELSE 0 END) as not_spoilt_fruits
		SQL
		)
			->whereBetween('harvest_date', [$from, $to])
			->first();

		return response()->json([
			'success' => true,
			'data' => [
				'from' => $from,
				'to' => $to,
				'totals' => $totals,
			],
		]);
	}

	/**
	 * Season summary: totals for a harvest season. Accepts optional from/to; otherwise uses full data range.
	 * Query params: from, to
	 */
	public function seasonSummary(Request $request)
	{
		$from = $request->input('from');
		$to = $request->input('to');

		if (! $from || ! $to) {
			$min = HarvestRecord::min('harvest_date');
			$max = HarvestRecord::max('harvest_date');
			$from = $from ?? $min;
			$to = $to ?? $max;
		}
		$totals = HarvestRecord::selectRaw(<<<SQL
			SUM(CASE WHEN spoilt THEN weight ELSE 0 END) as spoilt_weight,
			SUM(CASE WHEN NOT spoilt THEN weight ELSE 0 END) as not_spoilt_weight,
			SUM(CASE WHEN spoilt THEN num_of_fruits ELSE 0 END) as spoilt_fruits,
			SUM(CASE WHEN NOT spoilt THEN num_of_fruits ELSE 0 END) as not_spoilt_fruits
		SQL
		)
			->whereBetween('harvest_date', [$from, $to])
			->first();

		return response()->json([
			'success' => true,
			'data' => [
				'from' => $from,
				'to' => $to,
				'totals' => $totals,
			],
		]);
	}

		/**
		 * Return all active harvest events (where `active` = true).
		 */
		public function activeEvents(Request $request)
		{
			$events = HarvestEvent::where('active', true)
				->orderBy('start_date', 'desc')
				->get();

			return response()->json([
				'success' => true,
				'data' => $events,
			]);
	}

	/**
	 * Details for a date range aggregated by tree.
	 * Query params: from, to
	 */
	public function details(Request $request)
	{
		$from = $request->input('from');
		$to = $request->input('to');

		if (! $from || ! $to) {
			return response()->json([
				'success' => false,
				'message' => 'Please provide both from and to dates (YYYY-MM-DD).'
			], 422);
		}

		$rows = HarvestRecord::leftJoin('trees', 'harvest_records.tree_uuid', '=', 'trees.uuid')
			->selectRaw(<<<SQL
				harvest_records.tree_uuid as tree_uuid,
				trees.tree_tag as tree_tag,
				SUM(CASE WHEN spoilt THEN num_of_fruits ELSE 0 END) as spoilt_fruits,
				SUM(CASE WHEN NOT spoilt THEN num_of_fruits ELSE 0 END) as not_spoilt_fruits,
				SUM(CASE WHEN spoilt THEN weight ELSE 0 END) as spoilt_weight,
				SUM(CASE WHEN NOT spoilt THEN weight ELSE 0 END) as not_spoilt_weight
			SQL
			)
			->whereBetween('harvest_date', [$from, $to])
			->groupBy('harvest_records.tree_uuid', 'trees.tree_tag')
			->orderBy('trees.tree_tag')
			->get();

		return response()->json([
			'success' => true,
			'data' => [
				'from' => $from,
				'to' => $to,
				'details' => $rows,
			],
		]);
	}
}
