<?php

namespace App\Http\Controllers;

use App\Http\Requests\BonusReasonRequest;
use App\Models\BonusReason;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonusReasonController extends Controller
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

                $data = BonusReason::select('*')->latest();

                return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('title', function($row){
                        return $row->title;
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';

                        $btn .= ' <a href="'.route('bonus-reasons.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-data" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        $btn .= '&nbsp;';


                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-data action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })
                    // search customization
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $searchValue = $request->search['value'];
                            $query->where(function($q) use ($searchValue) {
                                $q->where('title', 'like', "%{$searchValue}%");
                            });
                        }
                    })
                    ->rawColumns(['title','action'])
                    ->make(true);
            }

            return view('admin.bonusReasons.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {

        return view('admin.bonusReasons.create');
    }
    public function store(BonusReasonRequest $request)
    {
        try
        {
            // Create record
            BonusReason::create([
                'title' => $request->title,
            ]);

            $notification=array(
                'message' => 'Successfully a Bonus Reasons has been added',
                'alert-type' => 'success',
            );

            return redirect()->route('bonus-reasons.index')->with($notification);

        } catch(Exception $e) {
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
    public function show(BonusReason $bonusReason)
    {
        return view('admin.bonusReasons.edit', compact('bonusReason'));
    }
    public function edit(BonusReason $bonusReason)
    {
        //
    }
    public function update(BonusReasonRequest $request, BonusReason $bonusReason)
    {
        try
        {
            $bonusReason->update([
                'title' => $request->title,
            ]);

            $notification=array(
                'message'=>'Successfully the Bonus Reason has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('bonus-reasons.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating Bonus Reason: ', [
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
    public function destroy(BonusReason $bonusReason)
    {
        try
        {
            $bonusReason->delete();

            return response()->json(['status'=>true, 'message'=>'Successfully the Bonus Reason has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting Bonus Reason: ', [
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
