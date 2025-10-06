<?php

namespace App\Http\Controllers;

use App\Models\Paymentmethod;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class PaymentmethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try
        {
            $data = Paymentmethod::latest()->get();
            return $data;
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            // $count = Paymentmethod::where('user_id',$request->user_id)->where('bank_name',$request->bank_name)->count();
            // if($count > 0)
            // {
            //     $notification=array(
            //         'message' => 'Already you have added the bank name',
            //         'alert-type' => 'error',
            //     );
            //     return redirect()->back()->with($notification);
            // }
            $method = Paymentmethod::updateOrCreate(
                ['user_id' => $request->user_id], // Search by user_id
                [
                    'mobile_no'       => $request->mobile_no,
                    'account_holder'  => $request->account_holder,
                    'account_number'  => $request->account_number,
                    'bank_name'       => $request->bank_name,
                    'branch_name'     => $request->branch_name,
                    'routing_number'  => $request->routing_number,
                ]
            );
            $notification=array(
                'message' => 'Successfully added',
                'alert-type' => 'success',
            );
            return redirect()->back()->with($notification);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paymentmethod  $paymentmethod
     * @return \Illuminate\Http\Response
     */
    public function show(Paymentmethod $paymentmethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paymentmethod  $paymentmethod
     * @return \Illuminate\Http\Response
     */
    public function edit(Paymentmethod $paymentmethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paymentmethod  $paymentmethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paymentmethod $paymentmethod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paymentmethod  $paymentmethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paymentmethod $paymentmethod)
    {
        //
    }

    public function addPaymentMethod(Request $request)
    {
        if($request->has('user_id'))
        {
            $user = User::findorfail($request->user_id);
            return view('user.payment_method', compact('user'));
        }else{
            return "Invalid Request";
        }
    }

    public function checkWithdraPassword(Request $request)
    {
        try
        {
            $user = User::findorfail($request->user_id);
            if (!Hash::check($request->withdraw_pass, $user->withdraw_password))
            {
                return response()->json(['status'=>false, 'message'=>'Wrong Withdraw Password']);
            }
            return response()->json(['status'=>true, 'message'=>'The withdraw password is right']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
