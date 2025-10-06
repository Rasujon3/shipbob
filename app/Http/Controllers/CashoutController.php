<?php

namespace App\Http\Controllers;

use App\Models\CashInImg;
use App\Models\FrozenAmount;
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
        // Calculate & check Reserved Amount
        $frozenData = FrozenAmount::where('user_id', Auth::user()->id)->first();
        if ($frozenData) {
            $notification = [
                'message' => 'Insufficient balance. Please contact with support team.',
                'alert-type' => 'error'
            ];

            return redirect()->route('user-profile')->with($notification);
        }
        $user = User::findorfail(Auth::user()->id);
        $payment_methods = Paymentmethod::where('user_id',$user->id)->get();
        $img = CashInImg::first();

        return view('user.cashout', compact('user','payment_methods', 'img'));
    }

    public function saveCashOut(CashOutRequest $request)
    {
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
	    		$notification = array(
	                'message' => 'Successfully your cashout request has been sent to admin',
	                'alert-type' => 'success'
	            );

	             return redirect()->route('user-profile')->with($notification);
            }

            $notification = array(
	                'message' => 'Invalid Withdraw amount',
	                'alert-type' => 'error'
	        );

	        return redirect()->route('user-profile')->with($notification);

    	}catch(Exception $e){
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
