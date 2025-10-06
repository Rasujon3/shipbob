<?php

namespace App\Http\Controllers;

use App\Http\Requests\BonusReasonRequest;
use App\Http\Requests\BonusRequest;
use App\Models\BonusHistroy;
use App\Models\BonusReason;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonusController extends Controller
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

                $data = BonusHistroy::with('user')->select('*')->latest();

                return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('name', function($row){
                        return optional($row->user)->username
                            ? "{$row->user->username} ({$row->user->uid})"
                            : 'N/A';
                    })

                    ->addColumn('title', function($row){
                        return $row->title;
                    })

                    ->addColumn('amount', function($row){
                        return $row->amount;
                    })

//                    ->addColumn('action', function($row){
//
//                        $btn = "";
//                        $btn .= '&nbsp;';
//
//                        # $btn .= ' <a href="'.route('bonus.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-data" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';
//
//                        $btn .= '&nbsp;';
//
//
//                        # $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-data action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';
//
//                        return $btn;
//                    })
//                    ->rawColumns(['name','title','amount','action'])
                    // search customization
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $searchValue = $request->search['value'];
                            $query->where(function($q) use ($searchValue) {
                                $q->where('title', 'like', "%{$searchValue}%")
                                    ->orWhere('amount', 'like', "%{$searchValue}%")
                                    ->orWhereHas('user', function ($uq) use ($searchValue) {
                                        $uq->where('username', 'like', "%{$searchValue}%")
                                            ->orWhere('uid', 'like', "%{$searchValue}%");
                                    });
                            });
                        }
                    })
                    ->rawColumns(['name','title','amount'])
                    ->make(true);
            }

            return view('admin.bonus.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->get();

        $bonusReasons = BonusReason::get();

        return view('admin.bonus.create', compact('users', 'bonusReasons'));
    }
    public function store(BonusRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $amount = $request->amount;
            if ($amount && $amount > 0) {
                $user = User::where('id', $request->user_id)->first();
                $user->main_balance = $user->main_balance == NULL ? $amount : round($user->main_balance + $amount, 2);
                $user->update();
            }

            // Create record
            BonusHistroy::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'amount' => $request->amount,
            ]);

            $notification=array(
                'message' => 'Successfully a Bonus Reasons has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('bonus.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing Bonus Reasons: ', [
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
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
}
