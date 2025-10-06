<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
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

class EventController extends Controller
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

               $products = Event::select('*')->latest();

                    return Datatables::of($products)
                        ->addIndexColumn()

                        ->addColumn('title', function($row){
                            return $row->title;
                        })

                        ->addColumn('img', function($row){
                            $url = asset($row->img);
                            return '<img src="' . $url . '" alt="Event Image" style="height:60px;">';
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';

                           $btn .= ' <a href="'.route('events.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-product" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-event action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        ->rawColumns(['title','img','action'])
                        ->make(true);
            }

            return view('admin.events.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.events.create');
    }
    public function store(EventRequest $request)
    {
        DB::beginTransaction();
        try
        {
            if($request->hasFile('file')) {
                $filePath = $this->storeFile($request->file('file'));
                $path = $filePath ?? '';
            }

            $event = new Event();
            $event->title = $request->title;
            $event->img = $path;
            $event->save();

            $notification=array(
                'message' => 'Successfully a event has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('events.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing event: ', [
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
    public function show(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }
    public function edit(Event $event)
    {
        //
    }
    public function update(EventRequest $request, Event $event)
    {
        try
        {
            // Handle file upload
            $path = $event->img;
            if ($request->hasFile('file')) {
                $filePath = $this->updateFile($request->file('file'), $event);
                $path = $filePath ?? '';
            }

            $event->title = $request->title;
            $event->img = $path;
            $event->save();

            $notification=array(
                'message'=>'Successfully the event has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('events.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating event: ', [
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
    public function destroy(Event $event)
    {
        try
        {
            // Delete the old file if it exists
            $this->deleteOldFile($event);
            $event->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the event has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting event: ', [
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
        $filePath = 'uploads/events'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('event_', true) . '.' . $file->getClientOriginalExtension();

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
        $filePath = 'uploads/events'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('event_', true) . '.' . $file->getClientOriginalExtension();

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
        if (!empty($data->img)) { # ensure from database
            // TODO: ensure from database (2)
            $oldFilePath = public_path($data->img); // Use without prepending $filePath
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
