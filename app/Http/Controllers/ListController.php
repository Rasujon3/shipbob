<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Cashout;
use App\Models\Cashin;
use App\Models\User;
use DataTables;
use DB;

class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function cashinLists(Request $request)
    {
    	if($request->ajax()){

               $data = Cashin::latest();

                return Datatables::of($data)
                    ->addIndexColumn()


                    ->addColumn('status', function($row){
                        $isApproved = $row->status == 'Approved';
                        $checkboxClass = $isApproved ? 'active-cashin' : 'decline-cashin';
                        $checked = $isApproved ? 'checked' : '';
                        $disabled = $isApproved ? 'disabled' : ''; // prevent toggling if approved

                        return '<label class="switch">
                                    <input class="' . $checkboxClass . '" id="status-cashin-update" type="checkbox" ' . $checked . ' ' . $disabled . ' data-id="' . $row->id . '">
                                    <span class="slider round"></span>
                                </label>';
                    })

                    ->addColumn('user', function($row){
                        return optional($row->user)->username
                            ? "{$row->user->username} ({$row->user->uid})"
                            : 'N/A';
                    })

                    ->addColumn('action', function($row){

                       $btn = "";
                       $btn .= '&nbsp;';


                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-cashin action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';



                        return $btn;
                    })
                    // search customization
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $searchValue = $request->search['value'];
                            $query->where(function($q) use ($searchValue) {
                                $q->where('amount', 'like', "%{$searchValue}%")
                                    ->orWhere('uuid', 'like', "%{$searchValue}%")
                                    ->orWhereHas('user', function ($uq) use ($searchValue) {
                                        $uq->where('username', 'like', "%{$searchValue}%")
                                            ->orWhere('uid', 'like', "%{$searchValue}%");
                                    });
                            });
                        }
                    })
                    ->rawColumns(['action','status','user'])
                    ->make(true);
        }
        return view('admin.lists.cashin');

    }

    public function cashoutLists(Request $request)
    {
    	if($request->ajax()){

               $data = Cashout::with('paymentMethod')->latest();

                return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('user', function($row){
                        return optional($row->user)->username
                            ? "{$row->user->username} ({$row->user->uid})"
                            : 'N/A';
                    })

                    ->addColumn('acc_no', function($row){
                        return optional($row->paymentMethod)->account_number
                            ? $row->paymentMethod->account_number
                            : 'N/A';
                    })

                    ->addColumn('method', function($row){
                        return optional($row->paymentMethod)->bank_name
                            ? $row->paymentMethod->bank_name
                            : 'N/A';
                    })

                    ->addColumn('status', function($row){
					    $isApproved = $row->status == 'Approved';
					    $checkboxClass = $isApproved ? 'active-cashout' : 'decline-cashout';
					    $checked = $isApproved ? 'checked' : '';
					    $disabled = $isApproved ? 'disabled' : ''; // prevent toggling if approved

					    return '<label class="switch">
					                <input class="' . $checkboxClass . '" id="status-cashout-update" type="checkbox" ' . $checked . ' ' . $disabled . ' data-id="' . $row->id . '">
					                <span class="slider round"></span>
					            </label>';
					})

                    ->addColumn('action', function($row){

                       $btn = "";
                       $btn .= '&nbsp;';


                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-cashout action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';



                        return $btn;
                    })
                    // search customization
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $searchValue = $request->search['value'];
                            $query->where(function($q) use ($searchValue) {
                                $q->where('amount', 'like', "%{$searchValue}%")
                                    ->orWhere('uuid', 'like', "%{$searchValue}%")
                                    ->orWhereHas('user', function ($uq) use ($searchValue) {
                                        $uq->where('username', 'like', "%{$searchValue}%")
                                            ->orWhere('uid', 'like', "%{$searchValue}%");
                                    });
                            });
                        }
                    })
                    ->rawColumns(['action','status','user', 'method', 'acc_no'])
                    ->make(true);
        }
        return view('admin.lists.cashout');
    }


    public function coStatusUpdate(Request $request)
    {
    	DB::beginTransaction();
    	try
    	{

    		$data = Cashout::findorfail($request->co_id);
    		$data->status = $request->status;
    		$data->update();
    		$user = User::findorfail($data->user_id);
    		$user->main_balance = round($user->main_balance - $data->amount, 2);
    		$user->update();
    		DB::commit();
    		return response()->json(['status'=>true, 'message'=>'Successfully updated']);
    	}catch(Exception $e){
    		DB::rollback();
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function deleteCashout($id)
    {
    	try
    	{
    		$data = Cashout::findorfail($id);
    		$data->delete();
    		return response()->json(['status'=>true, 'message'=>'Successfully deleted']);
    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function ciStatusUpdate(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $data = Cashin::findorfail($request->ci_id);
            $data->status = $request->status;
            $data->update();
            $user = User::findorfail($data->user_id);
            if($data->package_id != NULL)
            {

                $user->main_balance = round($user->main_balance + $data->amount + package($data->package_id)->bonus_amount, 2);
                $user->update();
            }else{
                $user->main_balance = round($user->main_balance + $data->amount, 2);
                $user->update();
            }
            DB::commit();
            return response()->json(['status'=>true, 'message'=>'Successfully updated']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function deleteCashin($id)
    {
        try
        {
            $data = Cashin::findorfail($id);
            $data->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
