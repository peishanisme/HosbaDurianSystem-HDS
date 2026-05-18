<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HarvestRecord;
use App\Models\HarvestEvent;
use App\Models\HarvestGrade;
use App\DataTransferObject\HarvestGradeDTO;
use App\Actions\PostHarvest\CreateHarvestGradeAction;
use App\Actions\PostHarvest\UpdateHarvestGradeAction;
use Carbon\Carbon;

class HarvestController extends Controller
{
	public function store(Request $request)
	{
		$validated = $request->validate([
			'harvest_uuid' => 'required|exists:harvest_events,uuid',
			'date' => 'required|date',
			'species_id' => 'nullable|exists:species,id',
			'grade' => 'nullable|string|max:255',
			'weight' => 'nullable|numeric',
		]);

		$dto = HarvestGradeDTO::fromArray($validated);
		$harvestGrade = (new CreateHarvestGradeAction())->handle($dto);

		return response()->json([
			'success' => true,
			'message' => 'Harvest grade created successfully.',
			'data' => $harvestGrade,
		], 201);
	}

	public function index()
	{
		$grades = HarvestGrade::with('harvestEvent')
			->with('species')
			->orderBy('date', 'desc')
			->orderBy('id', 'desc')
			->get();

		return response()->json([
			'success' => true,
			'data' => $grades,
		]);
	}

	public function show($id)
	{
		$grade = HarvestGrade::with(['harvestEvent', 'species'])->findOrFail($id);

		return response()->json([
			'success' => true,
			'data' => $grade,
		]);
	}

	public function update(Request $request, $id)
	{
		$validated = $request->validate([
			'harvest_uuid' => 'required|exists:harvest_events,uuid',
			'date' => 'required|date',
			'species_id' => 'nullable|exists:species,id',
			'grade' => 'nullable|string|max:255',
			'weight' => 'nullable|numeric',
		]);

		$harvestGrade = HarvestGrade::findOrFail($id);
		$dto = HarvestGradeDTO::fromArray(array_merge(['id' => $harvestGrade->id], $validated));
		$harvestGrade = (new UpdateHarvestGradeAction())->handle($harvestGrade, $dto);

		return response()->json([
			'success' => true,
			'message' => 'Harvest grade updated successfully.',
			'data' => $harvestGrade,
		]);
	}

	public function destroy($id)
	{
		$harvestGrade = HarvestGrade::findOrFail($id);
		$harvestGrade->delete();

		return response()->json([
			'success' => true,
			'message' => 'Harvest grade deleted successfully.',
		]);
	}

	public function getByDate(Request $request)
	{
		// Accepts either `start` (required) and optional `end`, or legacy `date` parameter.
		$start = $request->input('start') ?? $request->input('date');
		$end = $request->input('end');

		if (! $start) {
			return response()->json([
				'success' => false,
				'message' => 'Please provide a start date (YYYY-MM-DD) as the "start" query parameter.'
			], 422);
		}

		try {
			$parsedStart = Carbon::parse($start)->toDateString();
			$parsedEnd = $end ? Carbon::parse($end)->toDateString() : null;
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Invalid date format. Use YYYY-MM-DD for start/end.'
			], 422);
		}

		$query = HarvestGrade::with(['harvestEvent', 'species'])
			->orderBy('date', 'desc')
			->orderBy('id', 'desc');

		if ($parsedEnd) {
			$query->whereBetween('date', [$parsedStart, $parsedEnd]);
		} else {
			$query->whereDate('date', $parsedStart);
		}

		$grades = $query->get();

		return response()->json([
			'success' => true,
			'data' => $grades,
			'count' => $grades->count(),
		]);
	}

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
		// Use the currently active harvest event
		$event = HarvestEvent::where('active', true)
			->orderBy('start_date', 'desc')
			->first();

		if (! $event) {
			return response()->json([
				'success' => false,
				'message' => 'No active harvest event found.'
			], 404);
		}

		// Totals for the active harvest_uuid
		$totals = HarvestRecord::selectRaw(<<<SQL
			SUM(CASE WHEN spoilt THEN weight ELSE 0 END) as spoilt_weight,
			SUM(CASE WHEN NOT spoilt THEN weight ELSE 0 END) as not_spoilt_weight,
			SUM(CASE WHEN spoilt THEN num_of_fruits ELSE 0 END) as spoilt_fruits,
			SUM(CASE WHEN NOT spoilt THEN num_of_fruits ELSE 0 END) as not_spoilt_fruits
		SQL
		)
			->where('harvest_uuid', $event->uuid)
			->first();

		return response()->json([
			'success' => true,
			'data' => [
				'harvest_event' => $event,
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
