<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrozenAmountRequest;
use App\Http\Requests\PackageAssignRequest;
use App\Http\Requests\PackageRequest;
use App\Models\AssignPackage;
use App\Models\FrozenAmount;
use App\Models\Package;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PackageAssignController extends Controller
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

               $packages = AssignPackage::with('user', 'package')->select('*')->latest();

                    return Datatables::of($packages)
                        ->addIndexColumn()

                        ->addColumn('name', function($row){
                            return $row->user->name;
                        })

                        ->addColumn('package', function($row){
                            return $row->package->title;
                        })

                        ->addColumn('action', function($row){

                            $btn = "";
                            $btn .= '&nbsp;';

                            $btn .= ' <a href="'.route('package-assign.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-frozenAmount" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-AssignPackage action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        ->rawColumns(['name', 'package', 'action'])
                        ->make(true);
            }

            return view('admin.packageAssign.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        $users = User::where('role', 'user')->where('status', 'active')->get();
        $packages = Package::latest()->get();
        return view('admin.packageAssign.create', compact('users', 'packages'));
    }
    public function store(PackageAssignRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $assignPackage = new AssignPackage();
            $assignPackage->user_id = $request->user_id;
            $assignPackage->package_id = $request->package_id;
            $assignPackage->save();

            $notification=array(
                'message' => 'Successfully assign package.',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('package-assign.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing assign package: ', [
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
    public function show(AssignPackage $packageAssign)
    {
        $users = User::where('role', 'user')->where('status', 'active')->get();
        $packages = Package::latest()->get();
        return view('admin.packageAssign.edit', compact('packages', 'users', 'packageAssign'));
    }
    public function edit(AssignPackage $assignPackage)
    {
        //
    }
    public function update(PackageAssignRequest $request, AssignPackage $packageAssign)
    {
        try
        {
            $packageAssign->user_id = $request->user_id;
            $packageAssign->package_id = $request->package_id;
            $packageAssign->save();

            $notification=array(
                'message'=>'Successfully the Assign Package has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('package-assign.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating Assign Package: ', [
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
    public function destroy(AssignPackage $packageAssign)
    {
        try
        {
            $packageAssign->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the Assign Package has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting Assign Package: ', [
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
