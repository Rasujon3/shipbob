<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\PackageRequest;
use App\Models\AssignedTrialTask;
use App\Models\AssignTask;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use App\Models\TrialTask;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use DataTables;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
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
    public function store(OrderRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $user = auth()->user();
            $productId = $request->input('product_id');

            $alreadyOrdered = Order::where('user_id', $user->id)
                ->where('is_completed', false)
                ->where('product_id', $productId)
                ->exists();

            /*
            if ($alreadyOrdered) {
                $notification = array(
                    'message' => 'You have already ordered this product.',
                    'alert-type' => 'error'
                );
                return redirect()->route('user-setoff')->with($notification);
            }
            */

            Order::create([
                'order_number' => $this->generateOrderNumber($user->uid, $productId),
                'user_id' => $user->id,
                'product_id' => $productId,
                'amount' => product($productId)->commission,
                'completed_at' => Carbon::now(),
                'is_trial_task' => (bool)$request->input('is_trial_task'),
                'task_id' => $request->input('task_id'),
            ]);

            $user->main_balance = $user->main_balance == NULL ? product($productId)->commission : round($user->main_balance + product($productId)->commission, 2);
            $user->update();

            $orderCompletedCount = Order::where('user_id', Auth::user()->id)->where('is_completed', false)->count();

            // Total Task Count
            $totalTaskCount = 0;
            $trialTaskInfo = TrialTask::first();
            $assignTrialTask = AssignedTrialTask::where('user_id', Auth::user()->id)
                ->where('status', 'pending')
                ->first();

            if ($assignTrialTask) {
//                $totalTaskCount += $trialTaskInfo?->num_of_task ? (int)$trialTaskInfo?->num_of_task : 0;
                $totalTaskCount += $assignTrialTask->num_of_tasks ? (int) $assignTrialTask->num_of_tasks : 0;
            }

            // Trial Task
            $assignedTrialTask = AssignedTrialTask::where('user_id', $user->id)->first();

            // Another Task
            $assignTasks = AssignTask::with('task')
                ->where('user_id', Auth::user()->id)
                ->where('is_completed', false)
                ->get();

            if (count($assignTasks) > 0) {
                foreach ($assignTasks as $assignTask) {
//                    $num = (int) ($assignTask->task->num_of_task ?? 0);
                    $num = (int) ($assignTask?->num_of_tasks ?? 0);
                    $totalTaskCount += $num;
                }
            }

            if ($assignedTrialTask->status !== 'completed' && $totalTaskCount > 0 && $orderCompletedCount === $totalTaskCount) {
                $this->isTrialComplete($assignedTrialTask, $user);
            }

            if ($assignedTrialTask->status === 'completed' && $orderCompletedCount === $totalTaskCount) {

                $this->isTaskComplete($user);

//                $notification = array(
//                    'message' => 'Order placed successfully & complete the task.',
//                    'alert-type' => 'success'
//                );
//
//                DB::commit();
//
//                return redirect()->route('user-setoff')->with($notification);
            }


            $message = $assignedTrialTask->status === 'completed' && $orderCompletedCount === $totalTaskCount ? 'Order placed successfully & complete the task.' : 'Order placed successfully.';
            $notification = array(
                'message' => $message,
                'alert-type' => 'success'
            );

            DB::commit();

            return redirect()->route('user-setoff')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in storing order: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            DB::rollback();
            return redirect()->route('user-setoff')->with($notification);
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

    private function generateOrderNumber($uid, $productId)
    {
        do {
            // Generate order number using user ID, product ID, and current timestamp (YmdHis)
            $timestamp = now()->format('YmdHis');
            $orderNumber = 'O' . $uid . $productId . $timestamp;
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
    private function isTrialComplete($assignedTrialTask, $user)
    {
        $assignedTrialTask->status = 'completed';
        $assignedTrialTask->save();
        // Order trial task status update
        Order::where('user_id', $user->id)->where('is_trial_task', true)->update([ 'is_completed' => true ]);
        User::where('id', $user->id)->update([ 'balance' => 0 ]);
    }
    private function isTaskComplete($user)
    {
        AssignTask::where('user_id', Auth::user()->id)->where('is_completed', false)->update(['is_completed' => true]);
        // Order task status update
        Order::where('user_id', $user->id)->where('is_trial_task', false)->update([ 'is_completed' => true ]);
    }
}
