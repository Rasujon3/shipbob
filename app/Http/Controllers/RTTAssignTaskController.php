<?php

namespace App\Http\Controllers;

use App\Http\Requests\RTTTaskAssignRequest;
use App\Models\AssignedTrialTask;
use App\Models\AssignTask;
use App\Models\RTTAssignTask;
use App\Models\RTTTask;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RTTAssignTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index(Request $request)
    {
        try
        {
            if($request->ajax()){

                $packages = RTTAssignTask::with('user', 'rttTask')->select('*')->latest();

                return Datatables::of($packages)
                    ->addIndexColumn()

                    ->addColumn('name', function($row) {
                        return optional($row->user)->username
                            ? "{$row->user->username} ({$row->user->uid})"
                            : 'N/A';
                    })

                    ->addColumn('task', function($row) {
                        return $row->rttTask?->title ?? '';
                    })

                    ->addColumn('num_of_tasks', function($row) {
                        return $row->num_of_tasks ?? '';
                    })

                    ->addColumn('status', function($row) {
                        return $row->status;
                    })

                    ->addColumn('action', function($row) {

                        $btn = "";

                        $btn .= '&nbsp;';

                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-data action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })
                    // search customization
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $searchValue = $request->search['value'];
                            $query->where(function($q) use ($searchValue) {
                                $q->where('num_of_tasks', 'like', "%{$searchValue}%")
                                    # ->orWhere('is_completed', 'like', '%' . ($searchValue === 'Completed' ? '1' : '0') . '%')
                                    ->orWhereHas('user', function ($uq) use ($searchValue) {
                                        $uq->where('username', 'like', "%{$searchValue}%")
                                            ->orWhere('uid', 'like', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('task', function ($uq) use ($searchValue) {
                                        $uq->where('title', 'like', "%{$searchValue}%");
                                    });
                            });
                        }
                    })
                    ->rawColumns(['name', 'task', 'num_of_tasks', 'status', 'action'])
                    ->make(true);
            }

            return view('admin.rttTaskAssign.index');
        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        $assignedTrialTaskUsers = AssignedTrialTask::where('status', '!=', 'completed')->get('user_id');
        $assignedTaskUsers = AssignTask::where('is_completed', '0')->get('user_id');
        $assignedRTTTaskUsers = RTTAssignTask::where('status', '!=', 'Completed')->get('user_id');

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotIn('id', $assignedTrialTaskUsers)
            ->whereNotIn('id', $assignedTaskUsers)
            ->whereNotIn('id', $assignedRTTTaskUsers)
            ->latest()
            ->get();

        $tasks = RTTTask::latest()->get();
        return view('admin.rttTaskAssign.create', compact('users', 'tasks'));
    }
    public function store(RTTTaskAssignRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $trialTaskInfo = AssignedTrialTask::where('user_id', $request->user_id)->first();

            if(!$trialTaskInfo) {
                $notification=array(
                    'message' => "First assign Trial task.",
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }

            if($trialTaskInfo && $trialTaskInfo->status !== "completed") {
                $notification=array(
                    'message' => "Trial task not completed yet.",
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }

            $existingIncompleteTask = AssignTask::where('user_id', $request->user_id)
                ->where('is_completed', false)
                ->exists();

            if ($existingIncompleteTask) {
                $notification=array(
                    'message' => "This user already has an incomplete task assigned.",
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }

            /*
            $existingSameTask = AssignTask::where('user_id', $request->user_id)
                ->where('task_id', $request->task_id)
                ->exists();

            if ($existingSameTask) {
                $notification=array(
                    'message' => "This user already has assigned this task.",
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
            */
            $taskInfo = RTTTask::where('id', $request->rtt_task_id)->first();
            $setting = Setting::first();

            $taskAssign = new RTTAssignTask();
            $taskAssign->user_id = $request->user_id;
            $taskAssign->rtt_task_id = $request->rtt_task_id;
            $taskAssign->num_of_tasks = ($taskInfo && $taskInfo->num_of_task) ? $taskInfo->num_of_task : 0;
            $taskAssign->status = 'Incomplete';
            $taskAssign->save();

            $user = User::where('id', $request->user_id)->first();
            $user->balance = $user->balance == null
                ? $setting->rtt_task_assign_amount
                : round($user->balance + ($setting->rtt_trial_balance ?? 0), 2);
            $user->save();

            $notification = array(
                'message' => 'Successfully assign task.',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('rtt-assign-tasks.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing RTTAssignTask: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
    public function show(RTTAssignTask $taskAssign)
    {
        $assignedTrialTaskUsers = AssignedTrialTask::where('status', '!=', 'completed')->get('user_id');
        $assignedTaskUsers = AssignTask::where('is_completed', '1')->get('user_id');

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotIn('id', $assignedTrialTaskUsers)
            ->whereNotIn('id', $assignedTaskUsers)
            ->get();

        $tasks = Task::latest()->get();
        return view('admin.rttTaskAssign.edit', compact('tasks', 'users', 'taskAssign'));
    }
    public function edit(RTTAssignTask $assignLevel)
    {
        //
    }
    public function update(RTTTaskAssignRequest $request, AssignTask $taskAssign)
    {
        try
        {
            $taskInfo = Task::where('id', $request->task_id)->first();

            $taskAssign->user_id = $request->user_id;
            $taskAssign->task_id = $request->task_id;
            $taskAssign->num_of_tasks = ($taskInfo && $taskInfo->num_of_task) ? $taskInfo->num_of_task : 0;
            $taskAssign->save();

            $notification=array(
                'message'=>'Successfully the Assign Task has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('task-assign.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating Assign Task: ', [
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
    public function destroy(RTTAssignTask $taskAssign)
    {
        DB::beginTransaction();
        try
        {
            // 1st update balance for deleted task
            $commission = Order::where('user_id', $taskAssign->user_id)
                ->where('is_trial_task', false)
                ->where('task_id', $taskAssign->task_id)
                ->sum('amount');

            $user = User::where('id', $taskAssign->user_id)->first();
            $user->main_balance = round($user->main_balance - $commission, 2);
            $user->save();

            // 2nd tasks delete
            Order::where('user_id', $taskAssign->user_id)
                ->where('is_trial_task', false)
                ->where('task_id', $taskAssign->task_id)
                ->delete();

            // 3rd assigned tasks delete
            $taskAssign->delete();

            DB::commit();
            return response()->json(['status'=>true, 'message'=>'Successfully the Assign Task has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting Assign Task: ', [
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
