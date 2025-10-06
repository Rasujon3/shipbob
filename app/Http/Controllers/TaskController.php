<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Product;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
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

               $tasks = Task::select('*')->latest();

                    return Datatables::of($tasks)
                        ->addIndexColumn()

                        ->addColumn('title', function($row){
                            return $row->title;
                        })

                        ->addColumn('num_of_task', function($row){
                            return $row->num_of_task;
                        })

//                        ->addColumn('time_duration', function($row){
//                            return "$row->time_duration $row->time_unit";
//                        })

//                        ->addColumn('commission', function($row){
//                            return $row->commission;
//                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';

                           $btn .= ' <a href="'.route('tasks.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-product" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-task action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        // search customization
                        ->filter(function ($query) use ($request) {
                            if ($request->has('search') && $request->search['value'] != '') {
                                $searchValue = $request->search['value'];
                                $query->where(function($q) use ($searchValue) {
                                    $q->where('title', 'like', "%{$searchValue}%")
                                        ->orWhere('num_of_task', 'like', "%{$searchValue}%");
                                });
                            }
                        })
                        ->rawColumns(['title', 'num_of_task', 'time_duration', 'commission', 'action'])
                        ->make(true);
            }

            return view('admin.tasks.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.tasks.create');
    }
    public function store(TaskRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $task = new Task();
            $task->title = $request->title;
            $task->num_of_task = $request->num_of_task;
//            $task->commission = $request->commission;
//            $task->time_duration = $request->time_duration;
//            $task->time_unit = $request->time_unit;
            $task->save();

            $notification=array(
                'message' => 'Successfully a task has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('tasks.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing task: ', [
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
    public function show(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }
    public function edit(Task $task)
    {
        //
    }
    public function update(TaskRequest $request, Task $task)
    {
        try
        {
            $task->title = $request->title;
            $task->num_of_task = $request->num_of_task;
//            $task->commission = $request->commission;
//            $task->time_duration = $request->time_duration;
//            $task->time_unit = $request->time_unit;
            $task->save();

            $notification=array(
                'message'=>'Successfully the task has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('tasks.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating task: ', [
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
    public function destroy(Task $task)
    {
        try
        {
            $task->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the task has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting task: ', [
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
        $filePath = 'uploads/products'; # change path if needed
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
        $filePath = 'uploads/products'; # change path if needed
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
