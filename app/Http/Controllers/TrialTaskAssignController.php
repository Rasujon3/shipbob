<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskAssignRequest;
use App\Http\Requests\TrialTaskAssignRequest;
use App\Models\AssignedTrialTask;
use App\Models\AssignTask;
use App\Models\Order;
use App\Models\Task;
use App\Models\TrialTask;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Illuminate\Support\Facades\Log;

class TrialTaskAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function index(Request $request)
    {
        try
        {
            if($request->ajax()){

               $packages = AssignedTrialTask::with('user', 'trialTask')->select('*')->latest();

                    return Datatables::of($packages)
                        ->addIndexColumn()

                        ->addColumn('name', function($row) {
                            return optional($row->user)->username
                                ? "{$row->user->username} ({$row->user->uid})"
                                : 'N/A';
                        })

                        ->addColumn('task', function($row){
                            return "Trial Task";
                        })

                        ->addColumn('num_of_tasks', function($row){
                            return $row->num_of_tasks;
                        })

                        ->addColumn('status', function($row){
                            return $row->status;
                        })

                        ->addColumn('action', function($row){

                            $btn = "";
                            $btn .= '&nbsp;';

                            # $btn .= ' <a href="'.route('trial-task-assign.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-frozenAmount" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';
                            if ($row->status !== 'completed') {
                                $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-AssignedTrialTask action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';
                            }

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
                                        });
                                });
                            }
                        })
                        ->rawColumns(['name', 'task', 'num_of_tasks', 'status', 'action'])
                        ->make(true);
            }

            return view('admin.trialTaskAssign.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        $assignedTrialTaskUsers = AssignedTrialTask::get('user_id');
        $assignedTaskUsers = AssignTask::get('user_id');

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotIn('id', $assignedTrialTaskUsers)
            ->whereNotIn('id', $assignedTaskUsers)
            ->latest()
            ->get();

        $tasks = TrialTask::latest()->get();
        return view('admin.trialTaskAssign.create', compact('users', 'tasks'));
    }
    public function store(TrialTaskAssignRequest $request)
    {
        $assignTaskCount = AssignTask::where('user_id', $request->user_id)->count();
        if($assignTaskCount > 0){
            $notification=array(
                'message' => 'Already assigned another task.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        DB::beginTransaction();
        try
        {
            $trialTaskInfo = TrialTask::first();

            $assignLevel = new AssignedTrialTask();
            $assignLevel->user_id = $request->user_id;
            $assignLevel->trial_task_id = $request->trial_task_id;
            $assignLevel->num_of_tasks = ($trialTaskInfo && $trialTaskInfo->num_of_task) ? $trialTaskInfo->num_of_task : 0;
            $assignLevel->status = 'pending';
            $assignLevel->save();

            User::where('id', $assignLevel->user_id)->update(['balance' => $trialTaskInfo->trial_balance]);

            $notification=array(
                'message' => 'Successfully assign trial task.',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('trial-task-assign.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing assign trial Task: ', [
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
    public function show(AssignedTrialTask $trialTaskAssign)
    {
        $assignedTrialTaskUsers = AssignedTrialTask::get('user_id');
        $assignedTaskUsers = AssignTask::get('user_id');
        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotIn('id', $assignedTrialTaskUsers)
            ->whereNotIn('id', $assignedTaskUsers)
            ->get();
        $tasks = TrialTask::latest()->get();
        return view('admin.trialTaskAssign.edit', compact('tasks', 'users', 'trialTaskAssign'));
    }
    public function edit(AssignedTrialTask $assignLevel)
    {
        //
    }
    public function update(TrialTaskAssignRequest $request, AssignedTrialTask $trialTaskAssign)
    {
        try
        {
            $trialTaskAssign->user_id = $request->user_id;
            $trialTaskAssign->trial_task_id = $request->trial_task_id;
            $trialTaskAssign->save();

            $notification=array(
                'message'=>'Successfully the Assign Trial Task has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('trial-task-assign.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating Assign Trial Task: ', [
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
    public function destroy(AssignedTrialTask $trialTaskAssign)
    {
        if ($trialTaskAssign->status) {
            return response()->json(['status'=>false, 'message'=>'Assign task completed. Can not deleted.']);
        }

        DB::beginTransaction();
        try
        {
            // 1st update balance for deleted trial task
            $commission = Order::where('user_id', $trialTaskAssign->user_id)
                ->where('is_trial_task', true)
                ->where('task_id', null)
                ->sum('amount');

            $user = User::where('id', $trialTaskAssign->user_id)->first();
            $user->balance = 0;
            $user->main_balance = round($user->main_balance - $commission, 2);
            $user->save();

            // 2nd trial tasks delete
            Order::where('user_id', $trialTaskAssign->user_id)
                ->where('is_trial_task', true)
                ->where('task_id', null)
                ->delete();

            // 3rd assigned trial tasks delete
            $trialTaskAssign->delete();

            DB::commit();
            return response()->json(['status'=>true, 'message'=>'Successfully the Assign Trial Task has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting Assign Trial Task: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
//            return redirect()->back()->with($notification);
            return response()->json(['status'=>false, 'message'=>'Something went wrong!!!']);
        }
    }
}
