<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditAssignRequest;
use App\Http\Requests\TaskAssignRequest;
use App\Models\AssignCredit;
use App\Models\AssignedTrialTask;
use App\Models\AssignTask;
use App\Models\Credit;
use App\Models\Order;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Illuminate\Support\Facades\Log;

class CreditAssignController extends Controller
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

               $packages = AssignCredit::with('user', 'credit')->select('*')->latest();

                    return Datatables::of($packages)
                        ->addIndexColumn()

                        ->addColumn('name', function($row) {
                            return optional($row->user)->username
                                ? "{$row->user->username} ({$row->user->uid})"
                                : 'N/A';
                        })

                        ->addColumn('credit', function($row){
                            return $row->credit->title ?? '';
                        })

                        ->addColumn('img', function($row){
                            $url = asset($row->credit->img);
                            return '<img src="' . $url . '" alt="Credit Image" style="height:60px;">';
                        })

                        ->addColumn('action', function($row){

                            $btn = "";

                            if (!$row->is_completed) {
                                $btn .= '&nbsp;';
                                # $btn .= ' <a href="'.route('task-assign.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-frozenAmount" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';
                            }

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-data action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        // search customization
                        ->filter(function ($query) use ($request) {
                            if ($request->has('search') && $request->search['value'] != '') {
                                $searchValue = $request->search['value'];
                                $query->where(function($q) use ($searchValue) {
                                    $q->where('user_id', 'like', "%{$searchValue}%")
                                        # ->orWhere('is_completed', 'like', '%' . ($searchValue === 'Completed' ? '1' : '0') . '%')
                                        ->orWhereHas('user', function ($uq) use ($searchValue) {
                                            $uq->where('username', 'like', "%{$searchValue}%")
                                                ->orWhere('uid', 'like', "%{$searchValue}%");
                                        })
                                        ->orWhereHas('credit', function ($uq) use ($searchValue) {
                                            $uq->where('title', 'like', "%{$searchValue}%");
                                        });
                                });
                            }
                        })
                        ->rawColumns(['name', 'credit', 'img', 'action'])
                        ->make(true);
            }

            return view('admin.creditAssign.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        $assignedCreditUsers = AssignCredit::get('user_id');

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotIn('id', $assignedCreditUsers)
            ->latest()
            ->get();

        $credits = Credit::latest()->get();
        return view('admin.creditAssign.create', compact('users', 'credits'));
    }
    public function store(CreditAssignRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $creditInfo = Credit::where('id', $request->credit_id)->first();

            $creditAssign = new AssignCredit();
            $creditAssign->user_id = $request->user_id;
            $creditAssign->credit_id = $request->credit_id;
            $creditAssign->img = $creditInfo->img;
            $creditAssign->save();

            $notification=array(
                'message' => 'Successfully assign credit has been added.',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('credit-assign.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing assign credit: ', [
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
    public function show(AssignCredit $creditAssign)
    {
        $assignedTrialTaskUsers = AssignedTrialTask::where('status', '!=', 'completed')->get('user_id');
        $assignedTaskUsers = AssignCredit::where('is_completed', '1')->get('user_id');

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotIn('id', $assignedTrialTaskUsers)
            ->whereNotIn('id', $assignedTaskUsers)
            ->get();

        $tasks = Task::latest()->get();
        return view('admin.taskAssign.edit', compact('tasks', 'users', 'taskAssign'));
    }
    public function edit(AssignCredit $assignLevel)
    {
        //
    }
    public function update(CreditAssignRequest $request, AssignCredit $creditAssign)
    {
        try
        {
            $taskInfo = Task::where('id', $request->task_id)->first();

            $creditAssign->user_id = $request->user_id;
            $creditAssign->task_id = $request->task_id;
            $creditAssign->num_of_tasks = ($taskInfo && $taskInfo->num_of_task) ? $taskInfo->num_of_task : 0;
            $creditAssign->save();

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
    public function destroy(AssignCredit $creditAssign)
    {
        DB::beginTransaction();
        try
        {
            // 3rd assigned tasks delete
            $creditAssign->delete();

            DB::commit();
            return response()->json(['status'=>true, 'message'=>'Successfully the Assign Credit has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting Assign Credit: ', [
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
