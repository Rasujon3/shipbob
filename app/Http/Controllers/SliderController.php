<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SliderController extends Controller
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

               $products = Slider::select('*')->latest();

                    return Datatables::of($products)
                        ->addIndexColumn()

                        ->addColumn('img', function($row){
                            return "<img style='width: 60px; height:60px;' class='img-fluid' src='".$row->img."'>";
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';

                           $btn .= ' <a href="'.route('sliders.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-slider" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-slider action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        ->rawColumns(['img','action'])
                        ->make(true);
            }

            return view('admin.sliders.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.sliders.create');
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try
        {
            if($request->hasFile('img')) {
                $filePath = $this->storeFile($request->file('img'));
                $path = $filePath ?? '';
            }

            $slider = new Slider();
            $slider->img = $path;
            $slider->save();

            $notification=array(
                'message' => 'Successfully a slider has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('sliders.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing slider: ', [
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
    public function show(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }
    public function edit(Slider $slider)
    {
        //
    }
    public function update(Request $request, Slider $slider)
    {
        try
        {
            // Handle file upload
            $path = $slider->img;
            if ($request->hasFile('img')) {
                $filePath = $this->updateFile($request->file('img'), $slider);
                $path = $filePath ?? '';
            }

            $slider->img = $path;
            $slider->save();

            $notification=array(
                'message'=>'Successfully the slider has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('sliders.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating slider: ', [
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
    public function destroy(Slider $slider)
    {
        try
        {
            // Delete the old file if it exists
            $this->deleteOldFile($slider);
            $slider->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the slider has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting slider: ', [
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
        $filePath = 'uploads/sliders'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('slider_', true) . '.' . $file->getClientOriginalExtension();

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
        $filePath = 'uploads/sliders'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('slider_', true) . '.' . $file->getClientOriginalExtension();

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
