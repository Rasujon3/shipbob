<?php

namespace App\Http\Controllers;

use App\Models\AssignedTrialTask;
use App\Models\Country;
use App\Models\Product;
use App\Models\TrialTask;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UpdateUserController extends Controller
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

               $data = User::with('assignTask', 'order', 'trialTaskAssign', 'rttAssignTask', 'rttOrder')
                   ->where('role', 'user')
                   ->select('*')
                   ->latest();

                    return Datatables::of($data)
                        ->addIndexColumn()

                        ->addColumn('name', function($row){
                            $name = "$row->username ($row->uid)";
                            return $name;
                        })

                        ->addColumn('total_trial_task', function($row){
                            $total = $row->trialTaskAssign?->num_of_tasks ?? 0;
                            return $total;
                        })

                        ->addColumn('completed_trial_task', function($row){
                            $completed = $row->order
                                ->where('is_trial_task', true)
                                ->where('task_id', null)
                                # ->where('is_completed', true)
                                ->count();
                            return $completed;
                        })

                        ->addColumn('total_assigned_task', function($row){
                            $total = $row->assignTask->sum('num_of_tasks');
                            return $total;
                        })

                        ->addColumn('completed_task', function($row){
                            $completed = $row->order
                                ->where('task_id', '!=', null)
                                # ->where('is_completed', false)
                                ->count();
                            return $completed;
                        })

                        ->addColumn('remaining_task', function($row){
                            $total = $row->assignTask->sum('num_of_tasks');
                            $completed = $row->order
                                ->where('task_id', '!=', null)
                                # ->where('is_completed', false)
                                ->count();
                            return $total - $completed;
                        })

//                        ->addColumn('location', function ($row) {
//                            // Check if latitude and longitude are available
//                            if ($row->latitude && $row->longitude) {
//                                // Use Haversine formula to find the nearest country
//                                $country = Country::selectRaw("
//                            name,
//                            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
//                        ", [$row->latitude, $row->longitude, $row->latitude])
//                                    ->orderBy('distance', 'asc')
//                                    ->first();
//
//                                return $country ? $country->name : 'Unknown';
//                            }
//                            return 'Unknown'; // Fallback if coordinates are missing
//                        })

                        ->addColumn('location', function ($row) {
                            $location = $row->address ?? 'Unknown';
                            return $location;
                        })

                        /*
                        ->addColumn('total_assigned_rtt', function($row){
                            $total = $row->rttAssignTask->sum('num_of_tasks');
                            return $total;
                        })

                        ->addColumn('completed_rtt_task', function($row){
                            $completed = $row->rttOrder->count();
                            return $completed;
                        })

                        ->addColumn('remaining_rtt_task', function($row){
                            $total = $row->rttAssignTask->sum('num_of_tasks');
                            $completed = $row->rttOrder->count();
                            return $total - $completed;
                        })
                        */

                        ->addColumn('view_rtt', function($row){
                            return ' <button type="button" class="btn btn-warning btn-sm view-rtt" data-id="'.$row->id.'">
                                        <i class="fa fa-tasks"></i> RTT
                                     </button>';
                        })

                        ->addColumn('status', function($row){
                            return '<label class="switch"><input class="' . ($row->status === 'Active' ? 'active-user' : 'decline-user') . '" id="status-user-update"  type="checkbox" ' . ($row->status === 'Active' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                        })

                        ->addColumn('action', function($row) {

                           $btn = "";
                           # $btn .= '&nbsp;';

                           $btn .= '<a href="#" class="btn btn-info btn-sm view-data" data-id="'.$row->id.'"><i class="fa fa-eye"></i></a>';

                           $btn .= ' <a href="'.route('updateUser.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-product" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            # $btn .= '&nbsp;';

                            # $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-product action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        // search customization
                        ->filter(function ($query) use ($request) {
                            if ($request->has('search') && $request->search['value'] !== '') {
                                $searchValue = $request->search['value'];
                                $query->where(function($q) use ($searchValue) {
                                    $q->where('username', 'like', "%{$searchValue}%")
                                        ->orWhere('phone', 'like', "%{$searchValue}%")
                                        ->orWhere('uid', 'like', "%{$searchValue}%");
                                });
                            }
                        })
                        ->rawColumns([
                            'name',
                            'location',
                            'phone',
                            'status',
                            'total_trial_task',
                            'completed_trial_task',
                            'total_assigned_task',
                            'completed_task',
                            'remaining_task',
//                            'total_assigned_rtt',
//                            'completed_rtt_task',
//                            'remaining_rtt_task',
                            'view_rtt',
                            'action'
                        ])
                        ->make(true);
            }

            return view('admin.users.index');
        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.users.create');
    }
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try
        {
            if($request->hasFile('file')) {
                $filePath = $this->storeFile($request->file('file'));
                $path = $filePath ?? '';
            }

            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->commission = $request->commission;
            $product->file = $path;
            $product->save();

            $notification=array(
                'message' => 'Successfully a product has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('users.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing product: ', [
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
    public function show(User $updateUser)
    {
        if (request()?->ajax()) {
            return view('admin.users.view-modal', compact('updateUser'))->render();
        }
        return view('admin.users.view', compact('updateUser'));
    }
    public function edit(User $updateUser)
    {
        return view('admin.users.edit', compact('updateUser'));
    }
    public function withdrawPasswordEdit(User $updateUser)
    {
        return view('admin.users.withdrawPasswordEdit', compact('updateUser'));
    }
    public function update(Request $request, User $updateUser)
    {
        $request->validate([
            'new_login_password' => 'required|min:6|confirmed',
        ], [
            'new_login_password.required' => 'New login Password is required.',
            'new_login_password.min' => 'New login Password required minimum 6 characters.',
            'new_login_password.confirmed' => 'New login Password does not match.',
        ]);
        try
        {

            $updateUser->password = $request->new_login_password;
            $updateUser->save();

            $notification = [
                'message' => 'Login password has been changed successfully.',
                'alert-type' => 'success'
            ];

            return redirect()->route('updateUser.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating user: ', [
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
    public function withdrawPasswordUpdate(Request $request, User $updateUser)
    {
        $request->validate([
            'new_withdraw_password' => 'required|min:6|confirmed',
        ], [
            'new_withdraw_password.required' => 'New withdraw Password is required.',
            'new_withdraw_password.min' => 'New withdraw Password required minimum 6 characters.',
            'new_withdraw_password.confirmed' => 'New withdraw Password does not match.',
        ]);
        try
        {

            $updateUser->withdraw_password = $request->new_withdraw_password;
            $updateUser->save();

            $notification = [
                'message' => 'Withdraw password has been changed successfully.',
                'alert-type' => 'success'
            ];

            return redirect()->route('updateUser.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating user: ', [
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
    public function destroy(Product $product)
    {
        try
        {
            // Delete the old file if it exists
            $this->deleteOldFile($product);
            $product->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the product has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting product: ', [
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
    public function userStatusUpdate(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $user = User::findorfail($request->user_id);
            $user->status = $request->status;
            $user->update();

            $existingAssignedTask = AssignedTrialTask::where('user_id', $user->id)->first();
            if (!$existingAssignedTask) {
                $trialTaskInfo = TrialTask::first();

                $assignLevel = new AssignedTrialTask();
                $assignLevel->user_id = $user->id;
                $assignLevel->trial_task_id = $request->trial_task_id;
                $assignLevel->num_of_tasks = ($trialTaskInfo && $trialTaskInfo->num_of_task) ? $trialTaskInfo->num_of_task : 0;
                $assignLevel->status = 'pending';
                $assignLevel->save();

                $user->balance = $trialTaskInfo->trial_balance;
                $user->update();
            }

            DB::commit();

            return response()->json([
                'status'=>true,
                'message'=>"User status updated successfully."
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            // Log the error
            Log::error('Error in updating user: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'=>false,
                'message'=>"Something went wrong!!!"
            ]);
        }
    }

    public function updateLoginPassword(Request $request)
    {
        $request->validate([
            'new_login_password' => 'required|min:6|confirmed',
        ], [
            'new_login_password.required' => 'New login Password is required.',
            'new_login_password.min' => 'New login Password required minimum 6 characters.',
            'new_login_password.confirmed' => 'New login Password does not match.',
        ]);

        $user = Auth::user();

        $user->password = $request->new_login_password;
        $user->save();

        $notification = [
            'message' => 'Login password has been changed successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->route('updateUser.index')->with($notification);
    }

    public function rttStats($id)
    {
        try {
            $user = User::with('rttAssignTask', 'rttOrder')->findOrFail($id);

            $totalAssigned = $user->rttAssignTask->sum('num_of_tasks');
            $completed = $user->rttOrder->count();
            $remaining = $totalAssigned - $completed;

            return response()->json([
                'status' => true,
                'data' => [
                    'total_assigned_rtt' => $totalAssigned,
                    'completed_rtt_task' => $completed,
                    'remaining_rtt_task' => $remaining,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function storeFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/users'; # change path if needed
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
        $filePath = 'uploads/users'; # change path if needed
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
