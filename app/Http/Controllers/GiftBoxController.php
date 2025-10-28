<?php

namespace App\Http\Controllers;

use App\Http\Requests\GiftBoxRequest;
use App\Models\AssignedTrialTask;
use App\Models\Gift;
use App\Models\GiftBox;
use App\Models\Order;
use App\Models\RTTOrder;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DataTables;

class GiftBoxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function index(Request $request)
    {
        try
        {
            if($request->ajax()){

                $data = Gift::with('user', 'giftBox')->select('*')->latest();

                return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('name', function($row){
                        return optional($row->user)->username
                            ? "{$row->user->username} ({$row->user->uid})"
                            : 'N/A';
                    })

                    ->addColumn('task_will_block', function($row){
                        return $row->task_will_block;
                    })

                    ->addColumn('frozen_amounts', function($row){
                        return $row->frozen_amounts;
                    })

                    ->addColumn('frozen_amount_task_will_block', function($row){
                        return $row->frozen_amount_task_will_block;
                    })

                    ->addColumn('frozen_commission', function($row){
                        $data = $row->frozen_value . ' ' . $row->frozen_unit;
                        return $row->frozen_amounts ? $data : '';
                    })

                    ->addColumn('status', function($row){
                        $task_will_block = $row->task_will_block;
                        $userId = $row->user->id;
                        $completedTasksCount = Order::where('user_id', $userId)
                            #->where('task_id', '!=', null)
                            ->where('is_completed', false)
                            ->count();

                        $completedRttTasksCount = RTTOrder::where('user_id', $userId)
                            ->where('status', 'Incomplete')
                            ->count();

                        $checked = (($task_will_block == $completedTasksCount) || ($task_will_block == $completedRttTasksCount))
                            ? 'checked'
                            : '';

                        return '<label class="switch">
//                                    <input class="decline-cashin" id="status-cashin-update" type="checkbox" ' . $checked . ' ' . 'disabled' . ' data-id="' . $row->id . '">
//                                    <span class="slider round"></span>
//                                </label>';
                    })

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';

                        $btn .= ' <a href="'.route('gifts.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-data" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                        $btn .= '&nbsp;';


                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-data action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })
                    // search customization
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $searchValue = $request->search['value'];
                            $query->where(function($q) use ($searchValue) {
                                $q->where('task_will_block', 'like', "%{$searchValue}%")
                                    ->orWhere('frozen_amounts', 'like', "%{$searchValue}%")
                                    ->orWhere('frozen_amount_task_will_block', 'like', "%{$searchValue}%")
                                    ->orWhereHas('user', function ($uq) use ($searchValue) {
                                        $uq->where('username', 'like', "%{$searchValue}%")
                                            ->orWhere('uid', 'like', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('giftBox', function ($uq) use ($searchValue) {
                                        $uq->where('value', 'like', "%{$searchValue}%");
                                    });
                            });
                        }
                    })
                    ->rawColumns(['name','task_will_block','frozen_amounts','frozen_amount_task_will_block', 'frozen_commission','status', 'action'])
                    ->make(true);
            }

            return view('admin.gifts.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        $giftUsers = Gift::get('user_id');

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotIn('id', $giftUsers)
            ->latest()
            ->get();

        return view('admin.gifts.create', compact('users'));
    }
    public function store(GiftBoxRequest $request)
    {
        /*
        $assignedTrialTask = AssignedTrialTask::where('user_id', $request->user_id)->first();
        if (!$assignedTrialTask) {
            $notification=array(
                'message' => 'Assign Trial Task first.',
                'alert-type' => 'error'
            );
            return redirect()->route('gifts.index')->with($notification);
        }

        if ($assignedTrialTask && $assignedTrialTask->status !== 'completed') {
            $notification=array(
                'message' => 'Trial Task not completed yet.',
                'alert-type' => 'error'
            );
            return redirect()->route('gifts.index')->with($notification);
        }
        */
        DB::beginTransaction();
        try
        {
            // Create gift record
            $gift = Gift::create([
                'user_id' => $request->user_id,
                'task_will_block' => $request->task_will_block,
                'frozen_amounts' => $request->frozen_amounts,
                'frozen_amount_task_will_block' => $request->frozen_amount_task_will_block,
                'frozen_value' => $request->frozen_value,
                'frozen_unit' => $request->frozen_unit,
            ]);

            // Create gift boxes
            if (count($request->gift_boxes) > 0) {
                foreach ($request->gift_boxes as $index => $boxData) {
                    GiftBox::create([
                        'gift_id' => $gift->id,
                        'value' => $boxData['value'],
                        'unit' => $boxData['unit'],
                        'is_active' => ($request->active_gift == $index) ? 1 : 0,
                    ]);
                }
            }

            $notification=array(
                'message' => 'Successfully a gift has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('gifts.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing gift: ', [
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
    public function show(Gift $gift)
    {
        $giftUsers = Gift::get('user_id');

        $users = User::where('role', 'user')
            ->where('status', 'active')
            #->whereNotIn('id', $giftUsers)
            ->get();

        $gift->load(['giftBox', 'user']);
        return view('admin.gifts.edit', compact('gift', 'users'));
    }
    public function edit(Gift $gift)
    {
        //
    }
    public function update(GiftBoxRequest $request, Gift $gift)
    {
        try
        {
            // Update main gift record
            $gift->update([
                'user_id' => $request->user_id,
                'task_will_block' => $request->task_will_block,
                'frozen_amounts' => $request->frozen_amounts,
                'frozen_amount_task_will_block' => $request->frozen_amount_task_will_block,
                'frozen_value' => $request->frozen_value,
                'frozen_unit' => $request->frozen_unit,
            ]);

            // Delete old gift boxes
            $gift->giftBox()->delete();

            // Insert updated gift boxes
            if (count($request->gift_boxes) > 0) {
                foreach ($request->gift_boxes as $index => $boxData) {
                    GiftBox::create([
                        'gift_id' => $gift->id,
                        'value'   => $boxData['value'],
                        'unit'    => $boxData['unit'],
                        'is_active' => ($request->active_gift == $index) ? 1 : 0,
                    ]);
                }
            }

            $notification=array(
                'message'=>'Successfully the gift has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('gifts.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating gift: ', [
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
    public function destroy(Gift $gift)
    {
        try
        {
            // Delete related gift boxes first
            $gift->giftBox()->delete();

            // Delete main gift record
            $gift->delete();

            return response()->json(['status'=>true, 'message'=>'Successfully the gift has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting gift: ', [
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
