<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrozenAmountRequest;
use App\Http\Requests\PackageRequest;
use App\Models\AssignedTrialTask;
use App\Models\FrozenAmount;
use App\Models\Order;
use App\Models\Product;
use App\Models\RTTOrder;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FrozenAmountController extends Controller
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

               $packages = FrozenAmount::with('user')->select('*')->latest();

                    return Datatables::of($packages)
                        ->addIndexColumn()

                        ->addColumn('name', function($row){
                            return optional($row->user)->username
                                ? "{$row->user->username} ({$row->user->uid})"
                                : 'N/A';
                        })

                        ->addColumn('amount', function($row){
                            return $row->amount;
                        })

                        ->addColumn('task_will_block', function($row){
                            return $row->task_will_block;
                        })

                        ->addColumn('commission', function($row){
                            $commission = $row->value . ' ' . $row->unit;
                            return $commission;
                        })

                        ->addColumn('status', function($row){
                            $task_will_block = $row->task_will_block;
                            $userId = $row->user->id;
                            $completedTasksCount = Order::where('user_id', $userId)
                                ->where('task_id', '!=', null)
                                ->where('is_completed', false)
                                ->count();

                            $completedRttTasksCount = RTTOrder::where('user_id', $userId)
                                ->where('status', 'Incomplete')
                                ->count();

                            $checked = (($task_will_block == $completedTasksCount) || ($task_will_block == $completedRttTasksCount))
                                ? 'checked'
                                : '';

                            return '<label class="switch">
//                                    <input class="decline-cashin" id="status-cashin-update" type="checkbox" ' . $checked . ' ' . 'disabled' . ' data-id="' . $row->id . '">
//                                    <span class="slider round"></span>
//                                </label>';
                        })

                        ->addColumn('action', function($row){

                            $btn = "";
                            $btn .= '&nbsp;';

                            # $btn .= ' <a href="'.route('frozen-amounts.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-frozenAmount" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            # $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-frozenAmount action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        // search customization
                        ->filter(function ($query) use ($request) {
                            if ($request->has('search') && $request->search['value'] != '') {
                                $searchValue = $request->search['value'];
                                $query->where(function($q) use ($searchValue) {
                                    $q->where('amount', 'like', "%{$searchValue}%")
                                        ->orWhere('task_will_block', 'like', "%{$searchValue}%")
                                        ->orWhereHas('user', function ($uq) use ($searchValue) {
                                            $uq->where('username', 'like', "%{$searchValue}%")
                                                ->orWhere('uid', 'like', "%{$searchValue}%");
                                        });
                                });
                            }
                        })
                        ->rawColumns(['name', 'amount', 'task_will_block', 'commission', 'status', 'action'])
                        ->make(true);
            }

            return view('admin.frozenAmounts.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->latest()
            ->get();
        return view('admin.frozenAmounts.create', compact('users'));
    }
    public function store(FrozenAmountRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $user = User::findorfail($request->user_id);
            // Check Trial Task Completed
            $assignedTrialTaskData = AssignedTrialTask::where('user_id', $request->user_id)->first();
            if (!$assignedTrialTaskData || ($assignedTrialTaskData && $assignedTrialTaskData->status === 'pending')) {
                $userData = $user->username . ' (' . $user->uid . ')';
                $notification = [
                    'message' => $userData . ' has not completed the trial task yet.',
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }

            $frozenAmount = new FrozenAmount();
            $frozenAmount->user_id = $request->user_id;
            $frozenAmount->amount = $request->amount;
            $frozenAmount->task_will_block = $request->task_will_block;
            $frozenAmount->value = $request->value;
            $frozenAmount->unit = $request->unit;
            $frozenAmount->save();

            # $user->main_balance = $user->main_balance == NULL?$frozenAmount->amount:$user->main_balance+$frozenAmount->amount;
            # $user->update();

            $notification=array(
                'message' => 'Successfully a frozen Amount has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('frozen-amounts.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing frozen Amount: ', [
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
    public function show(FrozenAmount $frozenAmount)
    {
        $users = User::where('role', 'user')->where('status', 'active')->get();
        return view('admin.frozenAmounts.edit', compact('frozenAmount', 'users'));
    }
    public function edit(FrozenAmount $frozenAmount)
    {
        //
    }
    public function update(FrozenAmountRequest $request, FrozenAmount $frozenAmount)
    {
        DB::beginTransaction();
        try
        {
            $user = User::findorfail($request->user_id);
            // Check Trial Task Completed
            $assignedTrialTaskData = AssignedTrialTask::where('user_id', $request->user_id)->first();
            if (!$assignedTrialTaskData || ($assignedTrialTaskData &&$assignedTrialTaskData->status === 'pending')) {
                $userData = $user->username . ' (' . $user->uid . ')';
                $notification = [
                    'message' => $userData . ' has not completed the trial task yet.',
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }
            $frozenAmount->user_id = $request->user_id;
            $frozenAmount->amount = $request->amount;
            $frozenAmount->task_will_block = $request->task_will_block;
            $frozenAmount->save();

            # $user->main_balance = $user->main_balance == NULL?$frozenAmount->amount:$user->main_balance+$frozenAmount->amount;
            # $user->update();

            $notification=array(
                'message'=>'Successfully the frozen Amount has been updated',
                'alert-type'=>'success',
            );

            DB::commit();

            return redirect()->route('frozen-amounts.index')->with($notification);

        }catch(Exception $e) {
            // Log the error
            Log::error('Error in updating frozen Amount: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            DB::rollback();
            return redirect()->back()->with($notification);
        }
    }
    public function destroy(FrozenAmount $frozenAmount)
    {
        try
        {
            $frozenAmount->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the frozen Amount has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting frozen Amount: ', [
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
