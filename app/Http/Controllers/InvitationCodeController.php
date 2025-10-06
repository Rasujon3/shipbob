<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationCodeRequest;
use App\Models\InvitationCode;
use App\Models\User;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvitationCodeController extends Controller
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

                $invitationCodes = InvitationCode::with('user')->select('*')->latest();

                return Datatables::of($invitationCodes)
                    ->addIndexColumn()

                    ->addColumn('name', function($row){
                        return optional($row->user)->username
                            ? "{$row->user->username} ({$row->user->uid})"
                            : 'N/A';
                    })

                    ->addColumn('code', function($row){
                        return $row->code;
                    })

                    ->addColumn('note', function($row){
                        return $row->note;
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';

                        $btn .= ' <a href="'.route('invitation-codes.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-product" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        $btn .= '&nbsp;';


                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-item action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })
                    // search customization
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $searchValue = $request->search['value'];
                            $query->where(function($q) use ($searchValue) {
                                $q->where('code', 'like', "%{$searchValue}%")
                                    ->orWhere('note', 'like', "%{$searchValue}%")
                                    ->orWhereHas('user', function ($uq) use ($searchValue) {
                                        $uq->where('username', 'like', "%{$searchValue}%")
                                            ->orWhere('uid', 'like', "%{$searchValue}%");
                                    });
                            });
                        }
                    })
                    ->rawColumns(['name', 'code', 'note', 'action'])
                    ->make(true);
            }

            return view('admin.invitationCode.index');
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in retrieving invitationCode: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        $users = User::where('status', 'active')->get();
        return view('admin.invitationCode.create', compact('users'));
    }
    public function store(InvitationCodeRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $invitationCode = new InvitationCode();
            $invitationCode->user_id = $request->user_id;
            $invitationCode->code = $request->code;
            $invitationCode->note = $request->note;
            $invitationCode->save();

            $notification=array(
                'message' => 'Successfully a code has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('invitation-codes.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing code: ', [
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
    public function show(InvitationCode $invitationCode)
    {
        $users = User::where('role', 'user')->where('status', 'active')->get();
        return view('admin.invitationCode.edit', compact('invitationCode', 'users'));
    }
    public function edit(InvitationCode $invitationCode)
    {

    }
    public function update(InvitationCodeRequest $request, InvitationCode $invitationCode)
    {
        try
        {
            $invitationCode->user_id = $request->user_id;
            $invitationCode->code = $request->code;
            $invitationCode->note = $request->note;
            $invitationCode->save();

            $notification=array(
                'message'=>'Successfully the product has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('invitation-codes.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating product: ', [
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
    public function destroy(InvitationCode $invitationCode)
    {
        try
        {
            $invitationCode->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the product has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting InvitationCode: ', [
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
    public function generateCode()
    {
        $code = $this->generateUniqueInvitationCode();

        if ($code) {
            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'code' => $code
            ]);
        }
        return response()->json([
            'status' => false,
            'statusCode' => 400,
            'code' => ''
        ]);
    }

    /**
     * @throws RandomException
     */
    private function generateUniqueInvitationCode()
    {
        do {
            // generate 6 letters + 3 digits
            $letters = Str::lower(Str::random(6)); // random 6 letters
            $numbers = random_int(100, 999);             // random 3 digit number
            $code = $letters . $numbers;

        } while (InvitationCode::where('code', $code)->exists());

        return (string) $code;
    }
}
