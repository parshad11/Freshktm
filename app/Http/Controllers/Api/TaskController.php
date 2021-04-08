<?php

namespace App\Http\Controllers\Api;

use App\Delivery;
use App\DeliveryPerson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Task;
use Auth;
use DB;

class TaskController extends Controller
{
	public function index()
	{
		if (!auth()->user()->can('task.view') && !auth()->user()->can('view_own_task')) {
			abort(403, 'Unauthorized action.');
		}
		if (auth()->user()->user_type == 'delivery' || auth()->user()->user_type == 'Delivery') {
			$delivery_person = DeliveryPerson::where('user_id', auth()->user()->id)->first();
			$task = Task::where('delivery_person_id', $delivery_person->id)->get();
		} elseif (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'Admin') {
			$task = Task::all();
		}
		return response()->json([
			'data' => $task
		]);
	}

	public function update(Request $request,$id)
	{
		if (!auth()->user()->can('task.view')) {
			abort(403, 'Unauthorized action.');
		}
		try {
			$task = Task::findorfail($id);
			DB::beginTransaction();
			$update_data['task_status'] = $request->input('task_status');
			$task->update($update_data);

			if ($task->task_status == 'received') {
				$task->started_at = null;
				$task->ended_at = null;
				$task->save();
			}
			if ( $task->task_status == 'on process') {
				$started_at = now();
				$task->started_at = $started_at;
				$task->save();
			}
			if ($task->task_status == 'completed') {
				$ended_at = now();
				$task->ended_at = $ended_at;
				$task->save();
			}

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			\Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

			return $this->respondWentWrong($e);
		}
		$task = Task::where('id',$id)->get();
		return response()->json([
			'data'=>$task
		]);
	}
}
