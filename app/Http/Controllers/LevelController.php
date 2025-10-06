<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Requests\LevelRequest;
use App\Http\Requests\PackageRequest;
use App\Models\Event;
use App\Models\Level;
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

class LevelController extends Controller
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

               $events = Level::select('*')->latest();

                    return Datatables::of($events)
                        ->addIndexColumn()

                        ->addColumn('title', function($row){
                            return $row->title;
                        })

                        ->addColumn('description', function($row){
                            return $row->description;
                        })

                        ->addColumn('action', function($row){

                            $btn = "";
                            $btn .= '&nbsp;';

                            $btn .= ' <a href="'.route('levels.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-level" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';

                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-level action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        ->rawColumns(['title','description','action'])
                        ->make(true);
            }

            return view('admin.levels.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.levels.create');
    }
    public function store(LevelRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $level = new Level();
            $level->title = $request->title;
            $level->description = $request->description;
            $level->save();

            $notification=array(
                'message' => 'Successfully a level has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('levels.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing level: ', [
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
    public function show(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }
    public function edit(Level $level)
    {
        //
    }
    public function update(LevelRequest $request, Level $level)
    {
        try
        {

            $level->title = $request->title ?? $level->title;
            $level->description = $request->description ?? $level->description;
            $level->save();

            $notification=array(
                'message'=>'Successfully the level has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('levels.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating level: ', [
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
    public function destroy(Level $level)
    {
        try
        {
            $level->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the level has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting level: ', [
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
