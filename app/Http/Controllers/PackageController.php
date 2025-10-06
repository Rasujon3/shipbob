<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackageRequest;
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

class PackageController extends Controller
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

               $packages = Package::select('*')->latest();

                    return Datatables::of($packages)
                        ->addIndexColumn()

                        ->addColumn('title', function($row){
                            return $row->title;
                        })

                        ->addColumn('recharge_amount', function($row){
                            return $row->recharge_amount;
                        })

                        ->addColumn('bonus_amount', function($row){
                            return $row->bonus_amount;
                        })

                        ->addColumn('description', function($row){
                            return $row->description;
                        })

                        ->addColumn('action', function($row){

                            $btn = "";
                            if ($row->editable === 'Yes') {
                                $btn .= '&nbsp;';

                                $btn .= ' <a href="'.route('packages.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-package" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';
                            }

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-package action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        ->rawColumns(['title','recharge_amount','bonus_amount','description','action'])
                        ->make(true);
            }

            return view('admin.packages.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.packages.create');
    }
    public function store(PackageRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $package = new Package();
            $package->title = $request->title;
            $package->recharge_amount = $request->recharge_amount;
            $package->bonus_amount = $request->bonus_amount;
            $package->description = $request->description;
            $package->editable = 'Yes';
            $package->save();

            $notification=array(
                'message' => 'Successfully a package has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('packages.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing package: ', [
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
    public function show(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }
    public function edit(Package $package)
    {
        //
    }
    public function update(PackageRequest $request, Package $package)
    {
        try
        {
            $package->title = $request->title ?? $package->title;
            $package->recharge_amount = $request->recharge_amount ?? $package->recharge_amount;
            $package->bonus_amount = $request->bonus_amount ?? $package->bonus_amount;
            $package->description = $request->description ?? $package->description;
            $package->editable = 'Yes';
            $package->save();

            $notification=array(
                'message'=>'Successfully the package has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('packages.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating package: ', [
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
    public function destroy(Package $package)
    {
        try
        {
            $package->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the package has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting package: ', [
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

    private function storeFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/packages'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('package_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function updateFile($file, $data)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/packages'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('package_', true) . '.' . $file->getClientOriginalExtension();

        // Delete the old file if it exists
        $this->deleteOldFile($data);

        // Move the new file to the destination directory
        $file->move($directory, $fileName);

        // Store path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function deleteOldFile($data)
    {
        // TODO: ensure from database
        if (!empty($data->file)) { # ensure from database
            // TODO: ensure from database (2)
            $oldFilePath = public_path($data->file); // Use without prepending $filePath
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // Delete the old file
                return true;
            } else {
                Log::warning('Old file not found for deletion', ['path' => $oldFilePath]);
                return false;
            }
        }
    }
}
