<?php

namespace App\Http\Controllers;

use App\Http\Requests\WelcomeBonusRequest;
use App\Models\BonusHistroy;
use App\Models\User;
use App\Models\WelcomeBonus;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Illuminate\Support\Facades\Log;

class WelcomeBonusController extends Controller
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

                $packages = WelcomeBonus::with('user')->select('*')->latest();

                return Datatables::of($packages)
                    ->addIndexColumn()

                    ->addColumn('name', function($row) {
                        return optional($row->user)->username
                            ? "{$row->user->username} ({$row->user->uid})"
                            : 'N/A';
                    })

                    ->addColumn('amount', function($row){
                        return $row->amount ?? '';
                    })

                    ->addColumn('num_of_tasks', function($row){
                        return $row->num_of_tasks ?? '';
                    })

                    ->addColumn('completed_tasks', function($row){
                        return $row->completed_tasks ?? '';
                    })

                    ->addColumn('remaining_tasks', function($row){
                        return $row->remaining_tasks ?? '';
                    })

                    ->addColumn('status', function($row){
                        return $row->status ?? '';
                    })

                    ->addColumn('action', function($row){

                        $btn = "";

                        if ($row->status !== 'Complete') {
                            $btn .= '&nbsp;';
                            # $btn .= ' <a href="'.route('welcome-bonuses.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-frozenAmount" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';
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
                                    ->orWhere('status', 'like', "%{$searchValue}%")
                                    ->orWhereHas('user', function ($uq) use ($searchValue) {
                                        $uq->where('username', 'like', "%{$searchValue}%")
                                            ->orWhere('uid', 'like', "%{$searchValue}%");
                                    });
                            });
                        }
                    })
                    ->rawColumns([
                        'name',
                        'amount',
                        'num_of_tasks',
                        'completed_tasks',
                        'remaining_tasks',
                        'status',
                        'action'
                    ])
                    ->make(true);
            }

            return view('admin.welcomeBonus.index');
        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        $assignedWBonusUsers = WelcomeBonus::where('status', '!=', 'Complete')->get('user_id');

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotIn('id', $assignedWBonusUsers)
            ->latest()
            ->get();

        return view('admin.welcomeBonus.create', compact('users'));
    }
    public function store(WelcomeBonusRequest $request)
    {
        DB::beginTransaction();
        try
        {
            // 1st update main balance
            $amount = $request->amount ?? 0;
            $user = User::where('id', $request->user_id)->first();
            $user->main_balance = $user->main_balance == NULL ? $amount : round($user->main_balance + $amount, 2);
            $user->update();

            // 2nd bonus history create
            BonusHistroy::create([
                'user_id' => $request->user_id,
                'title' => 'Welcome Bonus',
                'amount' => $request->amount,
            ]);

            // 3rd Welcome Bonus create
            $welcomeBonus = new WelcomeBonus();
            $welcomeBonus->user_id = $request->user_id;
            $welcomeBonus->amount = $amount;
            $welcomeBonus->num_of_tasks = $request->num_of_tasks;
            $welcomeBonus->completed_tasks = 0;
            $welcomeBonus->remaining_tasks = $request->num_of_tasks;
            $welcomeBonus->status = 'Incomplete';
            $welcomeBonus->save();

            $notification=array(
                'message' => 'Successfully welcome bonus has been added.',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('welcome-bonuses.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing WelcomeBonus: ', [
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
    public function destroy(WelcomeBonus $welcomeBonus)
    {
        DB::beginTransaction();
        try
        {
            // 1st update main balance
            $amount = $welcomeBonus->amount ?? 0;
            $user = User::where('id', $welcomeBonus->user_id)->first();
            $user->main_balance = $user->main_balance == NULL ? 0 : round($user->main_balance - $amount, 2);
            $user->update();

            // 2nd Bonus History delete
            /*
            $bonusHistory = BonusHistroy::where('title', 'Welcome Bonus')
                ->where('user_id', $welcomeBonus->user_id)
                ->delete();
            */

            // 3rd Welcome Bonus delete
            $welcomeBonus->delete();

            DB::commit();
            return response()->json(['status'=>true, 'message'=>'Successfully the Welcome Bonus has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting Welcome Bonus: ', [
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
