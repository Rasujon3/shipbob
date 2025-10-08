<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\AssignedTrialTask;
use App\Models\AssignTask;
use App\Models\BonusHistroy;
use App\Models\CashInImg;
use App\Models\ContactSectionContent;
use App\Models\CourierSectionContent;
use App\Models\CreditRule;
use App\Models\DeliverySectionContent;
use App\Models\Event;
use App\Models\FrozenAmount;
use App\Models\Gift;
use App\Models\GiftBox;
use App\Models\GiftBoxContent;
use App\Models\GlobalInfo;
use App\Models\GrowthSectionContent;
use App\Models\HelpCenter;
use App\Models\HeroSectionContent;
use App\Models\Level;
use App\Models\LoginPageContent;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use App\Models\SetOffVideoSectionContent;
use App\Models\Setting;
use App\Models\ShippingSectionContent;
use App\Models\SignUpContent;
use App\Models\Slider;
use App\Models\TrialTask;
use App\Models\User;
use App\Services\GeocodingService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Cashin;
use App\Models\Cashout;

class UserController extends Controller
{
    public function index()
    {
        $heroSection = HeroSectionContent::first();
        $growthSection = GrowthSectionContent::first();
        $shippingSection = ShippingSectionContent::first();
        $courierSection = CourierSectionContent::first();
        $deliverySection = DeliverySectionContent::first();
        $contactSection = ContactSectionContent::first();
        $globalSection = GlobalInfo::first();

        return view('user.index', compact(
            'heroSection',
            'growthSection',
            'shippingSection',
            'courierSection',
            'deliverySection',
            'contactSection',
            'globalSection'
        ));
    }
    public function profile()
    {
        return view('user.profile');
    }
    public function setoff()
    {
        $user = User::with('frozenAmount', 'assignTrialTask')->where('id', Auth::user()->id)->first();

        # $frozenAmount = $user?->frozenAmount?->amount ?? '0';
        $frozenAmount = 0;
        // Completed Task Count
        $completedTaskCount = 0;
        $taskId = null;
        $isTrialTask = false;

        $userTrialTask = AssignedTrialTask::where('user_id', Auth::user()->id)
            ->where('status', 'pending')
            ->first();

        if ($userTrialTask) {
            $isTrialTask = true;
        }

        $userTask = AssignTask::where('user_id', Auth::user()->id)
            ->where('is_completed', false)
            ->first();

        if ($userTask) {
            $taskId = $userTask->task_id;
        }

        if (!$isTrialTask && !empty($taskId)) {
            $completedTaskCount = Order::where('user_id', Auth::user()->id)
                ->where('task_id', $taskId)
                ->where('is_completed', false)
                ->count();
        } else {
            $completedTaskCount = Order::where('user_id', Auth::user()->id)
                ->where('is_trial_task', true)
                ->where('task_id', null)
                ->where('is_completed', false)
                ->count();
        }

        // Check & Calculate Reserved Amount
        $frozenData = FrozenAmount::where('user_id', Auth::user()->id)->first();

        if ($frozenData && $frozenData->task_will_block == $completedTaskCount) {
            if (count($user?->frozenAmount) > 0) {
                foreach ($user?->frozenAmount as $item) {
                    $number = (int) ($item?->amount ?? 0);
                    $frozenAmount += $number;
                }
            }
        }

        $orderCompletedCount = Order::where('user_id', Auth::user()->id)->where('is_completed', false)->count();
        $commissionSum = Order::where('user_id', Auth::user()->id)
            ->where('is_completed', false)
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->sum(DB::raw('CAST(products.commission AS DECIMAL(10,2))'));

        $completedOrders = Order::where('user_id', Auth::user()->id)
//            ->where('is_completed', false)
            ->whereDate('completed_at', Carbon::today())
            ->with('product')
            ->latest()
            ->get();

        // Ordered product IDs
        $orderedProductIds = $completedOrders->pluck('product_id')->toArray();

        // Pending products (not ordered yet)
        $pendingProducts = Product::whereNotIn('id', $orderedProductIds)->latest()->limit(1)->get();

        // Task count
        $totalTaskCount = 0;
        $trialTaskInfo = TrialTask::first();

        $assignTrialTask = AssignedTrialTask::where('user_id', Auth::user()->id)
            ->where('status', 'pending')
            ->first();

        if ($assignTrialTask) {
//            $totalTaskCount += $trialTaskInfo?->num_of_task ? (int)$trialTaskInfo?->num_of_task : 0;
            $totalTaskCount += $assignTrialTask->num_of_tasks ? (int)$assignTrialTask->num_of_tasks : 0;
        }

        $assignTasks = AssignTask::with('task')
            ->where('user_id', Auth::user()->id)
            ->where('is_completed', false)
            ->get();

        $task_id = null;

        if (count($assignTasks) > 0) {
            foreach ($assignTasks as $assignTask) {
//                $num = (int) ($assignTask->task->num_of_task ?? 0);
                $num = (int) ($assignTask?->num_of_tasks ?? 0);
                $totalTaskCount += $num;

                $task_id = $assignTask->task?->id;
            }
        }

        $video = SetOffVideoSectionContent::first();
        $sliders = Slider::get();

        $is_trial_task = !(count($assignTasks) > 0);

        return view('user.setoff', compact(
            'frozenAmount',
            'orderCompletedCount',
            'commissionSum',
            'completedOrders',
            'totalTaskCount',
            'pendingProducts',
            'video',
            'sliders',
            'is_trial_task',
            'task_id'
        ));
    }
    public function event()
    {
        $events = Event::latest()->get();
        $packages = Package::latest()->get();
        return view('user.event', compact('events','packages'));
    }
    public function creditScore()
    {
        $rules = CreditRule::first();
        return view('user.credit-score', compact('rules'));
    }
    public function userLogin()
    {
        $loginPageContents = LoginPageContent::first();
        $signUpContents = SignUpContent::first();
        return view('user.login', compact('loginPageContents', 'signUpContents'));
    }
    public function loginUser(Request $request)
    {
        try {
            $data = $request->all();

            if (Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
                $user = Auth::user();

                // Save ip
                $user->ip_address = $request->ip();

                // get lat, long
                $latitude = $request->latitude;
                $longitude = $request->longitude;

                $addressData = null;
                if (!empty($latitude) && !empty($longitude)) {
                    // Geocoding service call
                    $geocodingService = new GeocodingService();
                    $addressData = $geocodingService->getAddressFromLatLong($latitude, $longitude);
                }

                if ($addressData) {
                    $user->country = $addressData['country'];
                    $user->state = $addressData['state'];
                    $user->city = $addressData['city'];
                    $user->village = $addressData['village'];
                    $user->area = $addressData['area'];
                    $user->address = $addressData['address'];
                }

                $user->latitude = $latitude;
                $user->longitude = $longitude;

                $user->save();

                if ($user->role === 'user') {
                    $notification = array(
                        'message' => 'Successfully Logged In',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('user-index')->with($notification);
                } else {
                    Auth::logout();
                    $notification = array(
                        'message' => 'You are not authorized to login here.',
                        'alert-type' => 'error'
                    );
                    return Redirect()->back()->with($notification);
                }

            } else {
                $notification = array(
                    'message' => 'Username or Password Invalid',
                    'alert-type' => 'error'
                );
                return Redirect()->back()->with($notification);
            }
        } catch (Exception $e) {
            Log::error('Error in Login: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            return Redirect()->back()->with($notification);
        }
    }
    public function signUp(Request $request)
    {
        // Validation
        $request->validate([
            # 'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            # 'withdraw_acc_number' => 'required|string',
            'withdraw_password' => 'required|string|min:6',
            'invitation_code' => 'required|string|exists:invitation_codes,code',
        ]);
        try {
            // Create new user
            $user = new User();
            $user->uid = $this->generateUniqueUid();
            # $user->name = $request->name;
            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->role = 'user'; // default role user
            $user->password = $request->password; // auto hashed via mutator
            # $user->withdraw_acc_number = $request->withdraw_acc_number;
            $user->withdraw_password = $request->withdraw_password;
            $user->invitation_code = $request->invitation_code;
            # $user->balance = setting()->trial_amount;
            $user->status = 'Inactive';
            $user->save();

            $notification = [
                'message' => 'Account created successfully. Please contact administrator for active your account.',
                'alert-type' => 'success'
            ];

            return redirect()->route('login-user')->with($notification);

        } catch (Exception $e) {
            Log::error('SignUp Error: '.$e->getMessage());

            $notification = [
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }
    private function generateUniqueUid()
    {
        do {
            $lastUser = User::orderBy('id', 'desc')->first();

            if (!$lastUser || !$lastUser->uid) {
                $uid = 6182;
            } else {
                $uid = (int) $lastUser->uid + 1;
            }

        } while (User::where('uid', $uid)->exists());

        return (string) $uid;
    }
    public function productOrder(Request $request)
    {
        $isTrialTask = $request->get('is_trial_task');
        $taskId = $request->get('task_id');

        try
        {
            $user = auth()->user();

            // check trial task assign
            $checkTrialTaskAssign = AssignedTrialTask::where('user_id', $user->id)->first();
            if (!$checkTrialTaskAssign) {
                $notification = [
                    'message' => 'You are not assigned on Trial Task. Please contact with support team.',
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }

            // Complete trial task & another task assigned or not
            $anotherPendingTaskAssigned = AssignTask::where('user_id', $user->id)
                ->where('is_completed', false)
                ->exists();

            if ($checkTrialTaskAssign && $checkTrialTaskAssign->status === 'completed' && !$anotherPendingTaskAssigned) {
                $notification = [
                    'message' => 'You are not assigned on another Task. Please contact with support team.',
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }

            // Total Task Count
            $totalTaskCount = 0;
            $trialTaskInfo = TrialTask::first();
            if (!$trialTaskInfo) {
                $notification = [
                    'message' => 'Trial Task not found. Please contact with support team.',
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }

            $assignTrialTask = AssignedTrialTask::where('user_id', Auth::user()->id)
                ->where('status', 'pending')
                ->first();

            if ($assignTrialTask) {
//                $totalTaskCount += $trialTaskInfo?->num_of_task ? (int)$trialTaskInfo?->num_of_task : 0;
                $totalTaskCount += $assignTrialTask->num_of_tasks ? (int)$assignTrialTask->num_of_tasks : 0;
            }

            $assignTasks = AssignTask::with('task')
                ->where('user_id', Auth::user()->id)
                ->where('is_completed', false)
                ->get();

            $task_id = null;

            if (count($assignTasks) > 0) {
                foreach ($assignTasks as $assignTask) {
//                    $num = (int) ($assignTask->task->num_of_task ?? 0);
                    $num = (int) ($assignTask?->num_of_tasks ?? 0);
                    $totalTaskCount += $num;

                    $task_id = $assignTask->task?->id;
                }
            }

            // Completed Task Count
            $completedTaskCount = 0;
            if (!$isTrialTask && !empty($taskId)) {
                $completedTaskCount = Order::where('user_id', Auth::user()->id)
                    ->where('task_id', $taskId)
                    ->where('is_completed', false)
                    ->count();
            } else {
                $completedTaskCount = Order::where('user_id', Auth::user()->id)
                    ->where('is_trial_task', true)
                    ->where('task_id', null)
                    ->where('is_completed', false)
                    ->count();
            }

            // Check Task Completed
            if ($completedTaskCount === $totalTaskCount) {
                $notification = [
                    'message' => 'You are completed assigned Task. Please contact with support team for new task.',
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }

            // Calculate & check Gift
            $giftData = Gift::where('user_id', Auth::user()->id)->first();
            if ($giftData && $giftData->task_will_block == $completedTaskCount) {
                $notification = [
                    'message' => 'Congratulation. You have a gift box.',
                    'alert-type' => 'success'
                ];

                return redirect()->route('gift-box')->with($notification);
            }

            // Calculate & check Reserved Amount
            $frozenData = FrozenAmount::where('user_id', Auth::user()->id)->first();
            if ($frozenData && $frozenData->task_will_block == $completedTaskCount) {
                $notification = [
                    'message' => 'Insufficient balance. Please contact with support team.',
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }


            $limit = $totalTaskCount - $completedTaskCount;

            $orderedProductIds = Order::where('user_id', $user->id)
//                ->where('is_completed', false)
                ->whereDate('completed_at', Carbon::today())
                ->pluck('product_id')
                ->toArray();

            $products = Product::latest()
                ->whereNotIn('id', $orderedProductIds)
                # ->limit($limit)
                ->limit(1)
                ->get();

            User::where('id', Auth::user()->id)->update(['balance' => 0]);

            if ($checkTrialTaskAssign && $checkTrialTaskAssign->status !== 'completed') {
                $checkTrialTaskAssign->started_at = Carbon::now();
                $checkTrialTaskAssign->save();
            }

            $is_trial_task = !(count($assignTasks) > 0);

            return view('user.orderProduct', compact(
                'products',
                'orderedProductIds',
                'is_trial_task',
                'task_id'
            ));
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in showing productOrder: ', [
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
    public function cashIn()
    {
        /*
        $user = User::with('paymentmethod')->find(user()->id);
        $packages = Package::get();
        $img = CashInImg::first();
        return view('user.profile.cashIn', compact('user','packages', 'img'));
        */
        return redirect()->route('user-technical-support');
    }
    public function accountDetails()
    {
        $fiveDeposits = Cashin::where('user_id',user()->id)->take(5)->latest()->get();
        $fiveWithdraws = Cashout::where('user_id',user()->id)->take(5)->latest()->get();
        $withdraws = Cashout::where('user_id',user()->id)->latest()->get();
        $deposits = Cashin::where('user_id',user()->id)->latest()->get();
        $commissions = Cashin::where('user_id',user()->id)->where('bouns_amount','!=',NULL)->latest()->get();

        return view('user.profile.accountDetails', compact('fiveDeposits','fiveWithdraws','withdraws','deposits','commissions'));
    }
    public function level()
    {
//        $levels = Level::with('assignLevel')->get();
//        return view('user.profile.level', compact('levels'));
        return view('user.profile.level_new');
    }
    public function levelDetails()
    {
        return view('user.profile.levelDetails');
    }
    public function signInDetails()
    {
        return view('user.profile.signInDetails');
    }
    public function technicalSupport()
    {
        $settings = Setting::first();
        return view('user.profile.technicalSupport', compact('settings'));
    }
    public function helpCenter()
    {
        $helpCenter = HelpCenter::get();
        return view('user.profile.helpCenter', compact('helpCenter'));
    }
    public function problemHelp()
    {
        return view('user.profile.problemHelp');
    }
    public function aboutUs()
    {
        return view('user.profile.aboutUs');
    }
    public function settings()
    {
        return view('user.profile.settings');
    }
    public function userAgreement()
    {
        $aboutUs = AboutUs::first();
        return view('user.aboutUs.userAgreement', compact('aboutUs'));
    }
    public function userPrivacy()
    {
        $aboutUs = AboutUs::first();
        return view('user.aboutUs.userPrivacy', compact('aboutUs'));
    }
    public function modifyWithdrawPassword()
    {
        return view('user.password.withdrawPassword');
    }
    public function modifyLoginPassword()
    {
        return view('user.password.loginPassword');
    }
    public function updateWithdrawPassword(Request $request)
    {
        $request->validate([
            'current_withdraw_password' => 'required',
            'new_withdraw_password' => 'required|min:6|confirmed',
        ], [
            'current_withdraw_password.required' => 'Current withdraw Password is required.',
            'new_withdraw_password.required' => 'New withdraw Password is required.',
            'new_withdraw_password.min' => 'Current withdraw Password required minimum 6 characters.',
            'new_withdraw_password.confirmed' => 'New withdraw Password does not match.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_withdraw_password, $user->withdraw_password)) {
            return back()->withErrors(['current_withdraw_password' => 'Current withdraw Password is incorrect.']);
        }

        if (Hash::check($request->new_withdraw_password, $user->withdraw_password)) {
            return back()->withErrors(['new_withdraw_password' => 'The new withdrawal password is the same as the previous one. Enter a new password.']);
        }

        $user->withdraw_password = $request->new_withdraw_password;
        $user->save();

        $notification = [
            'message' => 'Withdrawal password has been changed successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->route('user-profile')->with($notification);
    }
    public function updateLoginPassword(Request $request)
    {
        $request->validate([
            'current_login_password' => 'required',
            'new_login_password' => 'required|min:6|confirmed',
        ], [
            'current_login_password.required' => 'Current login Password is required.',
            'new_login_password.required' => 'New login Password is required.',
            'new_login_password.min' => 'Current login Password required minimum 6 characters.',
            'new_login_password.confirmed' => 'New login Password does not match.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_login_password, $user->password)) {
            return back()->withErrors(['current_login_password' => 'Current login Password is incorrect.']);
        }

        if (Hash::check($request->new_login_password, $user->password)) {
            return back()->withErrors(['new_login_password' => 'The new login password is the same as the previous one. Enter a new password.']);
        }

        $user->password = $request->new_login_password;
        $user->save();

        $notification = [
            'message' => 'Login password has been changed successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->route('user-profile')->with($notification);
    }
    public function passwordChange()
    {
        return view('admin.settings.change_password');
    }
    public function changePassword(Request $request)
    {
        try
        {
            $user = User::findorfail(Auth::user()->id);

            if (!Hash::check($request->current_password, $user->password)) {

                $notification=array(
                    'message' => 'The current password is not matched',
                    'alert-type' => 'error'
                );

                return redirect()->back()->with($notification);
            }

            if ($request->new_password !== $request->confirm_password) {

                $notification=array(
                    'message' => 'The new & confirm password are not matched',
                    'alert-type' => 'error'
                );

                return redirect()->back()->with($notification);
            }

            $user->password = $request->new_password;
            $user->update();


            $notification=array(
                'message' => 'Successfully your has been changed',
                'alert-type' => 'success'
            );

            return redirect()->route('dashboard')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in changePassword: ', [
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
    public function giftBox()
    {
        $contentData = GiftBoxContent::first();
        $userGift = Gift::with('giftBox')->where('user_id', Auth::user()->id)->first();

        return view('user.gift', compact('contentData', 'userGift'));
    }
    /**
     * AJAX route - for Gift box data retrieve
     */
    public function getGiftBoxData(Request $request)
    {
        try {
            // Current user এর gift data
            $userGift = Gift::with('giftBox')->where('user_id', Auth::user()->id)->first();

            if (!$userGift || count($userGift?->giftBox) <=0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No gift box found for you'
                ], 404);
            }

            // Gift boxes data prepare
            $giftBoxes = $userGift->giftBox->map(function($box, $index) {
                return [
                    'id' => $box->id,
                    'value' => $box->value,
                    'unit' => $box->unit,
                    'is_active' => $box->is_active,
                    'display_text' => "You get {$box->value} {$box->unit}",
                    'index' => $index
                ];
            });

            return response()->json([
                'success' => true,
                'gift_boxes' => $giftBoxes,
                'user_info' => [
                    'task_will_block' => $userGift->task_will_block,
                    'frozen_amounts' => $userGift->frozen_amounts,
                    'frozen_amount_task_will_block' => $userGift->frozen_amount_task_will_block
                ]
            ]);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in getGiftBoxData: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!!!'
            ], 500);
        }
    }

    /**
     * Call After Select Gift box
     */
    public function selectGiftBox(Request $request)
    {
        $request->validate([
            'gift_box_id' => 'required|integer|exists:gift_boxes,id'
        ]);

        try {
            $giftBoxId = $request->gift_box_id;
            $userId = Auth::user()->id;

            // Retrieve User's gift_boxes
            $userGift = Gift::with('giftBox')->where('user_id', $userId)->first();

            if (!$userGift || count($userGift?->giftBox) <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No gift box found for you'
                ], 404);
            }

            $failedGiftData = GiftBox::where('gift_id', $userGift?->id)->where('is_active', false)->get();
            $failDisplayText1 = '';
            $failDisplayText2 = '';

            $failDisplayText1 = $failedGiftData[0]['value'] . ' ' . $failedGiftData[0]['unit'];
            $failDisplayText2 = $failedGiftData[1]['value'] . ' ' . $failedGiftData[1]['unit'];

            $successGiftData = GiftBox::where('gift_id', $userGift->id)->where('is_active', true)->first();
            $amount = $successGiftData->value;
            $unit = $successGiftData->unit;

            DB::beginTransaction();
            // Calculate amount
            $user = User::findorfail($userGift->user_id);

            $bonusAmount = 0;
            if ($unit === 'Taka') {
                $user->main_balance = round($user->main_balance + $amount, 2);
                $bonusAmount = $amount;
            } else {
                $commissionSum = Order::where('user_id', Auth::user()->id)
                    ->where('is_completed', false)
                    ->join('products', 'orders.product_id', '=', 'products.id')
                    ->sum(DB::raw('CAST(products.commission AS DECIMAL(10,2))'));

                $oldBalance = $user->main_balance;
                $giftBalance = $commissionSum * $amount;
                $user->main_balance = round($user->main_balance + $giftBalance, 2);

                $bonusAmount = round($user->main_balance - $oldBalance, 2);
            }

            $user->update();

            // BonusHistroy save
            BonusHistroy::create([
                'user_id' => $userGift->user_id,
                'title'   => "Gift Box Bonus",
                'amount'  => $bonusAmount,
            ]);

            // delete Gift Box
            // First Delete related gift boxes
            $userGift->giftBox()->delete();

            // Delete main gift record
            $userGift->delete();

            if ($userGift->frozen_amounts && $userGift->frozen_amount_task_will_block && $userGift->user_id) {
                // Assign Frozen amount
                $frozenAmount = new FrozenAmount();
                $frozenAmount->user_id = $userGift->user_id;
                $frozenAmount->amount = $userGift->frozen_amounts;
                $frozenAmount->task_will_block = $userGift->frozen_amount_task_will_block;
                $frozenAmount->save();
            }

            // Array prepare
            $giftBoxes = $userGift->giftBox->map(function ($box) use ($giftBoxId) {
                return [
                    'id' => $box->id,
                    'value' => $box->value,
                    'unit' => $box->unit,
                    'is_active' => $box->id == $giftBoxId ? true : false,
                    'display_text' => "You get {$box->value} {$box->unit}"
                ];
            });

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Gift box selected successfully.',
                'gift_boxes' => $giftBoxes,
                'success_display_text' => "You got {$amount} {$unit}",
                'fail_display_text_1' => $failDisplayText1,
                'fail_display_text_2' => $failDisplayText2
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in selectGiftBox: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!!!'
            ], 500);
        }
    }

    /**
     * Call After Click Account
     */
    public function accountInfo()
    {
        $user = User::with('frozenAmount', 'assignTrialTask')->where('id', Auth::user()->id)->first();

        # $frozenAmount = $user?->frozenAmount?->amount ?? '0';
        $frozenAmount = 0;
        // Completed Task Count
        $completedTaskCount = 0;
        $taskId = null;
        $isTrialTask = false;

        $userTrialTask = AssignedTrialTask::where('user_id', Auth::user()->id)
            ->where('status', 'pending')
            ->first();

        if ($userTrialTask) {
            $isTrialTask = true;
        }

        $userTask = AssignTask::where('user_id', Auth::user()->id)
            ->where('is_completed', false)
            ->first();

        if ($userTask) {
            $taskId = $userTask->task_id;
        }

        if (!$isTrialTask && !empty($taskId)) {
            $completedTaskCount = Order::where('user_id', Auth::user()->id)
                ->where('task_id', $taskId)
                ->where('is_completed', false)
                ->count();
        } else {
            $completedTaskCount = Order::where('user_id', Auth::user()->id)
                ->where('is_trial_task', true)
                ->where('task_id', null)
                ->where('is_completed', false)
                ->count();
        }

        // Check & Calculate Reserved Amount
        $frozenData = FrozenAmount::where('user_id', Auth::user()->id)->first();

        if ($frozenData && $frozenData->task_will_block == $completedTaskCount) {
            if (count($user?->frozenAmount) > 0) {
                foreach ($user?->frozenAmount as $item) {
                    $number = (int) ($item?->amount ?? 0);
                    $frozenAmount += $number;
                }
            }
        }

        $orderCompletedCount = Order::where('user_id', Auth::user()->id)->where('is_completed', false)->count();
        $commissionSum = Order::where('user_id', Auth::user()->id)
            ->where('is_completed', false)
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->sum(DB::raw('CAST(products.commission AS DECIMAL(10,2))'));

        $completedOrders = Order::where('user_id', Auth::user()->id)
//            ->where('is_completed', false)
            ->whereDate('completed_at', Carbon::today())
            ->with('product')
            ->latest()
            ->get();

        // Ordered product IDs
        $orderedProductIds = $completedOrders->pluck('product_id')->toArray();

        // Pending products (not ordered yet)
        $pendingProducts = Product::whereNotIn('id', $orderedProductIds)->limit(1)->get();

        // Task count
        $totalTaskCount = 0;
        $trialTaskInfo = TrialTask::first();

        $assignTrialTask = AssignedTrialTask::where('user_id', Auth::user()->id)
            ->where('status', 'pending')
            ->first();

        if ($assignTrialTask) {
//            $totalTaskCount += $trialTaskInfo?->num_of_task ? (int)$trialTaskInfo?->num_of_task : 0;
            $totalTaskCount += $assignTrialTask->num_of_tasks ? (int)$assignTrialTask->num_of_tasks : 0;
        }

        $assignTasks = AssignTask::with('task')
            ->where('user_id', Auth::user()->id)
            ->where('is_completed', false)
            ->get();

        $task_id = null;

        if (count($assignTasks) > 0) {
            foreach ($assignTasks as $assignTask) {
//                $num = (int) ($assignTask->task->num_of_task ?? 0);
                $num = (int) ($assignTask?->num_of_tasks ?? 0);
                $totalTaskCount += $num;

                $task_id = $assignTask->task?->id;
            }
        }

        $video = SetOffVideoSectionContent::first();
        $sliders = Slider::get();

        $is_trial_task = !(count($assignTasks) > 0);

        return view('user.setoff', compact(
            'frozenAmount',
            'orderCompletedCount',
            'commissionSum',
            'completedOrders',
            'totalTaskCount',
            'pendingProducts',
            'video',
            'sliders',
            'is_trial_task',
            'task_id'
        ));
    }
}
