<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\Setting;
use App\Models\TrialTask;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrialTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function trialTask()
    {
        $trialTask = TrialTask::first();
        return view('admin.trialTask.trialTasks',compact('trialTask'));
    }
    public function updateTrialTask(Request $request)
    {
        try
        {
            $data = TrialTask::first();

            $defaults = [
                'num_of_task' => $data ? $data->num_of_task : null,
                'commission' => $data ? $data->commission : null,
                'time_duration' => $data ? $data->time_duration : null,
                'time_unit' => $data ? $data->time_unit : null,
            ];

            if ($data) {
                TrialTask::where('id', $data->id)->update(
                    [
                        'num_of_task' => $request->num_of_task ?? $defaults['num_of_task'],
                        'commission' => $request->commission ?? $defaults['commission'],
                        'time_duration' => $request->time_duration ?? $defaults['time_duration'],
                        'time_unit' => $request->time_unit ?? $defaults['time_unit'],
                    ]
                );
            } else {
                TrialTask::create(
                    [
                        'num_of_task' => $request->num_of_task ?? $defaults['num_of_task'],
                        'commission' => $request->commission ?? $defaults['commission'],
                        'time_duration' => $request->time_duration ?? $defaults['time_duration'],
                        'time_unit' => $request->time_unit ?? $defaults['time_unit'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating trialTask: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}
