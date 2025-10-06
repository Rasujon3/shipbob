<?php

namespace App\Http\Controllers;

use App\Models\HelpCenter;
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

class HelpCenterController extends Controller
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

               $products = HelpCenter::select('*')->latest();

                    return Datatables::of($products)
                        ->addIndexColumn()

                        ->addColumn('title', function($row){
                            return strip_tags($row->title);
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';

                           $btn .= ' <a href="'.route('helpCenter.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-product" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-helpCenter action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        ->rawColumns(['name','action'])
                        ->make(true);
            }

            return view('admin.helpCenter.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.helpCenter.create');
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try
        {

            $helpCenter = new HelpCenter();
            $helpCenter->title = $request->title;
            $helpCenter->description = $request->description;
            $helpCenter->save();

            $notification=array(
                'message' => 'Successfully a helpCenter has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('helpCenter.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing helpCenter: ', [
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
    public function show(HelpCenter $helpCenter)
    {
        return view('admin.helpCenter.edit', compact('helpCenter'));
    }
    public function edit(HelpCenter $helpCenter)
    {
        //
    }
    public function update(Request $request, HelpCenter $helpCenter)
    {
        try
        {
            $helpCenter->title = $request->title ?? $helpCenter->title;
            $helpCenter->description = $request->description ?? $helpCenter->description;
            $helpCenter->save();

            $notification=array(
                'message'=>'Successfully the helpCenter has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('helpCenter.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating helpCenter: ', [
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
    public function destroy(HelpCenter $helpCenter)
    {
        try
        {
            $helpCenter->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the product has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting helpCenter: ', [
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
        $filePath = 'uploads/helpCenter'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('product_', true) . '.' . $file->getClientOriginalExtension();

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
        $filePath = 'uploads/helpCenter'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('product_', true) . '.' . $file->getClientOriginalExtension();

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
