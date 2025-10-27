<?php

namespace App\Http\Controllers;

use App\Http\Requests\RTTTaskRequest;
use App\Models\RTTTask;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Illuminate\Support\Facades\Log;

class RTTTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function index(Request $request)
    {
        try
        {
            if($request->ajax()) {

                $tasks = RTTTask::select('*')->latest();

                return Datatables::of($tasks)
                    ->addIndexColumn()

                    ->addColumn('title', function($row){
                        return $row->title;
                    })

                    ->addColumn('num_of_task', function($row){
                        return $row->num_of_task;
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';

                        $btn .= ' <a href="'.route('rtt-tasks.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-product" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        $btn .= '&nbsp;';

                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-data action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

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

            return view('admin.rttTasks.index');
        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.rttTasks.create');
    }
    public function store(RTTTaskRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $task = new RTTTask();
            $task->title = $request->title;
            $task->num_of_task = $request->num_of_task;
            $task->save();

            $notification=array(
                'message' => 'Successfully a task has been added',
                'alert-type' => 'success',
            );

            DB::commit();

            return redirect()->route('rtt-tasks.index')->with($notification);

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
    public function show(RTTTask $rttTask)
    {
        return view('admin.rttTasks.edit', compact('rttTask'));
    }
    public function edit(RTTTask $rttTask)
    {
        //
    }
    public function update(RTTTaskRequest $request, RTTTask $rttTask)
    {
        try
        {
            $rttTask->title = $request->title;
            $rttTask->num_of_task = $request->num_of_task;
            $rttTask->save();

            $notification = array(
                'message'=>'Successfully the task has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('rtt-tasks.index')->with($notification);

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
    public function destroy(RTTTask $rttTask)
    {
        try
        {
            $rttTask->delete();
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

            $notification = array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}
