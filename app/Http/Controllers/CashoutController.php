<?php

namespace App\Http\Controllers;

use App\Models\AssignCredit;
use App\Models\AssignedTrialTask;
use App\Models\AssignTask;
use App\Models\CashInImg;
use App\Models\FrozenAmount;
use App\Models\Order;
use App\Models\RTTAssignTask;
use App\Models\Setting;
use App\Models\WelcomeBonus;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Models\Cashout;
use App\Models\Paymentmethod;
use App\Http\Requests\CashOutRequest;
use App\Models\Cashin;
use App\Http\Requests\CashInRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function cashout(Request $request)
    {
        try {
            $user = User::findorfail(Auth::user()->id);

            // Calculate & check Reserved Amount
            $frozenData = FrozenAmount::where('user_id', Auth::user()->id)->first();
            if ($frozenData) {
                $notification = [
                    'message' => 'Insufficient balance. Please contact with support team.',
                    'alert-type' => 'error'
                ];

                return redirect()->route('user-profile')->with($notification);
            }

            $incompleteTrialTask = false;
            $incompleteTask = false;
            $incompleteRttTask = false;

            $incompleteTrialTask = AssignedTrialTask::where('user_id', $user->id)
                ->where('status', '!=', 'completed')
                ->exists();

            $incompleteTask = AssignTask::where('user_id', $user->id)
                ->where('is_completed', '!=', true)
                ->exists();

            $incompleteRttTask = RTTAssignTask::where('user_id', $user->id)
                ->where('status', 'Incomplete')
                ->exists();

            if($incompleteTrialTask || $incompleteTask || $incompleteRttTask) {
                $notification = [
                    'message' => 'You have some incomplete tasks. Please complete them before making a withdrawal.',
                    'alert-type' => 'error'
                ];

                return redirect()->route('user-profile')->with($notification);
            }
            // Daily order completed count check
            $dailyOrderCount = Order::where('user_id', $user->id)
                ->whereDate('completed_at', now()->toDateString())
                ->count();
            $settings = Setting::first();
            $trialTaskCompleted = AssignedTrialTask::where('user_id', $user->id)
                ->where('status', 'completed')
                ->exists();

            $assignedTask = AssignTask::where('user_id', $user->id)->exists();
            if ($trialTaskCompleted && $assignedTask) {
                if($dailyOrderCount < $settings->daily_task_limit) {
                    $notification = [
                        'message' => "Need Daily Complete Minimum {$settings->daily_task_limit} Tasks for Withdraw.",
                        'alert-type' => 'error'
                    ];

                    return redirect()->route('user-profile')->with($notification);
                }
            }

            // Check Credit
            $assignedCredit = AssignCredit::with('credit')
                ->where('user_id', $user->id)
                ->first();
            if($assignedCredit) {
                $message = $assignedCredit->credit?->notice ?? 'You have no credits available for withdrawal. Please contact support.';
                $notification = [
                    'message' => $message,
                    'alert-type' => 'error'
                ];

                return redirect()->route('user-profile')->with($notification);
            }

            // Check Welcome Bonus
            $welcomeBonus = WelcomeBonus::where('user_id', $user->id)
                ->where('status', 'Incomplete')
                ->first();

            if($welcomeBonus) {
                $message = "You got welcome bonus. For withdraw, you need to complete minimum {$welcomeBonus?->num_of_tasks} tasks for welcome bonus.";
                $notification = [
                    'message' => $message,
                    'alert-type' => 'error'
                ];

                return redirect()->route('user-profile')->with($notification);
            }

            $payment_methods = Paymentmethod::where('user_id',$user->id)->get();
            $img = CashInImg::first();

            return view('user.cashout', compact('user','payment_methods', 'img'));
        } catch (Exception $e) {
                // Log the error
                Log::error('Error in cashout : ', [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);

            $notification = [
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];

            return redirect()->route('user-profile')->with($notification);
        }
    }

    public function saveCashOut(CashOutRequest $request)
    {
        DB::beginTransaction();
    	try
    	{
            if(user()->main_balance >= $request->amount)
            {
            	$cashOut = new Cashout;
	    		$cashOut->user_id = user()->id;
	    		$cashOut->uuid = time().user()->id;
	    		$cashOut->paymentmethod_id = $request->paymentmethod_id;
	    		$cashOut->amount = $request->amount;
	    		$cashOut->date = date('Y-m-d');
	    		$cashOut->time = date('h:i:s a');
                $cashOut->status = 'Pending';
	    		$cashOut->save();

                $user = User::findorfail(user()->id);
                $user->main_balance = round($user->main_balance - $request->amount, 2);
                $user->update();

                DB::commit();
	    		$notification = array(
	                'message' => 'Successfully your cashout request has been sent to admin',
	                'alert-type' => 'success'
	            );

	             return redirect()->route('user-profile')->with($notification);
            }

            DB::rollback();
            $notification = array(
	                'message' => 'Invalid Withdraw amount',
	                'alert-type' => 'error'
	        );

	        return redirect()->route('user-profile')->with($notification);

    	}catch(Exception $e){
            DB::rollback();
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }


    public function saveCashIn(CashInRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $cash_in = new Cashin();
            $cash_in->uuid = time().user()->id;
            $cash_in->user_id = user()->id;
            $cash_in->package_id = $request->selected_package_id;
            $cash_in->bouns_amount = $request->selected_package_id ? package($request->selected_package_id)->bonus_amount : null;
            $cash_in->amount = $request->amount;
            $cash_in->date = date('Y-m-d');
            $cash_in->time = date('h:i:s a');
            $cash_in->status = 'Pending';
            $cash_in->save();
            $notification = array(
                    'message' => 'Successfully your cash-in request has been sent to admin',
                    'alert-type' => 'success'
            );

            DB::commit();

            return redirect()->route('user-profile')->with($notification);
        }catch(Exception $e){
            // Log the error
            Log::error('Error in storing saveCashIn: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );

            DB::rollback();

            return redirect()->route('user-profile')->with($notification);
        }
    }
}
