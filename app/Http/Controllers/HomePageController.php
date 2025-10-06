<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\CashInImg;
use App\Models\ContactSectionContent;
use App\Models\CourierSectionContent;
use App\Models\CreditRule;
use App\Models\DeliverySectionContent;
use App\Models\GiftBoxContent;
use App\Models\GlobalInfo;
use App\Models\GrowthSectionContent;
use App\Models\HeroSectionContent;
use App\Models\LoginPageContent;
use App\Models\SetOffVideoSectionContent;
use App\Models\Setting;
use App\Models\ShippingSectionContent;
use App\Models\SignUpContent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function settings()
    {
        $setting = Setting::first();
        return view('admin.settings.settings',compact('setting'));
    }
    public function settingApp(Request $request)
    {
        try
        {
            $data = Setting::first();

            $defaults = [
                'trial_amount' => $data ? $data->trial_amount : null,
                'frozen_amount' => $data ? $data->frozen_amount : null,
                'no_of_trial_task' => $data ? $data->no_of_trial_task : null,
                'task_timing' => $data ? $data->task_timing : null,
                'telegram_group_link' => $data ? $data->telegram_group_link : null,
                'company_name' => $data ? $data->company_name : null,
                'img_url' => $data ? $data->company_logo : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('company_logo')) {
                $filePath = $this->storeFile($request->file('company_logo'));
                $img_url = $filePath;
            }

            if ($data) {
                Setting::where('id', $data->id)->update(
                    [
                        'trial_amount' => $request->trial_amount ?? $defaults['trial_amount'],
                        'frozen_amount' => $request->frozen_amount ?? $defaults['frozen_amount'],
                        'no_of_trial_task' => $request->no_of_trial_task ?? $defaults['no_of_trial_task'],
                        'task_timing' => $request->task_timing ?? $defaults['task_timing'],
                        'telegram_group_link' => $request->telegram_group_link ?? $defaults['telegram_group_link'],
                        'company_name' => $request->company_name ?? $defaults['company_name'],
                        'company_logo' => $request->hasFile('company_logo') ? $img_url : $defaults['img_url'],
                    ]
                );
            } else {
                Setting::create(
                    [
                        'trial_amount' => $request->trial_amount ?? $defaults['trial_amount'],
                        'frozen_amount' => $request->frozen_amount ?? $defaults['frozen_amount'],
                        'no_of_trial_task' => $request->no_of_trial_task ?? $defaults['no_of_trial_task'],
                        'task_timing' => $request->task_timing ?? $defaults['task_timing'],
                        'telegram_group_link' => $request->telegram_group_link ?? $defaults['telegram_group_link'],
                        'company_name' => $request->company_name ?? $defaults['company_name'],
                        'company_logo' => $request->hasFile('company_logo') ? $img_url : $defaults['img_url'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating settings: ', [
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

    public function heroSectionContent()
    {
        $heroSection = HeroSectionContent::first();
        return view('admin.homePageContents.heroSection',compact('heroSection'));
    }
    public function updateHeroSectionContent(Request $request)
    {
        try
        {
            $data = HeroSectionContent::first();

            $defaults = [
                'title' => $data ? $data->title : null,
                'sub_title' => $data ? $data->sub_title : null,
                'slogan' => $data ? $data->slogan : null,
                'banner_img' => $data ? $data->banner_img : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('banner_img')) {
                $filePath = $this->storeFile($request->file('banner_img'));
                $img_url = $filePath;
            }

            if ($data) {
                HeroSectionContent::where('id', $data->id)->update(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'sub_title' => $request->sub_title ?? $defaults['sub_title'],
                        'slogan' => $request->slogan ?? $defaults['slogan'],
                        'banner_img' => $request->hasFile('banner_img') ? $img_url : $defaults['banner_img'],
                    ]
                );
            } else {
                HeroSectionContent::create(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'sub_title' => $request->sub_title ?? $defaults['sub_title'],
                        'slogan' => $request->slogan ?? $defaults['slogan'],
                        'banner_img' => $request->hasFile('banner_img') ? $img_url : $defaults['banner_img'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating hero section: ', [
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

    public function videoSectionContent()
    {
        $heroSection = HeroSectionContent::first();
        return view('admin.homePageContents.videoSection',compact('heroSection'));
    }
    public function updateVideoSectionContent(Request $request)
    {
        try
        {
            $data = HeroSectionContent::first();

            $defaults = [
                'video_url' => $data ? $data->video_url : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('video_url')) {
                $filePath = $this->storeVideo($request->file('video_url'));
                $img_url = $filePath;
            }

            if ($data) {
                HeroSectionContent::where('id', $data->id)->update(
                    [
                        'video_url' => $request->hasFile('video_url') ? $img_url : $defaults['video_url'],
                    ]
                );
            } else {
                HeroSectionContent::create(
                    [
                        'video_url' => $request->hasFile('video_url') ? $img_url : $defaults['video_url'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating video section: ', [
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

    public function growthSectionContent()
    {
        $growthSection = GrowthSectionContent::first();
        return view('admin.homePageContents.growthSection',compact('growthSection'));
    }
    public function updateGrowthSectionContent(Request $request)
    {
        try
        {
            $data = GrowthSectionContent::first();

            $defaults = [
                'title' => $data ? $data->title : null,
                'sub_title' => $data ? $data->sub_title : null,
                'description' => $data ? $data->description : null,
                'img' => $data ? $data->img : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('img')) {
                $filePath = $this->storeGrowthFile($request->file('img'));
                $img_url = $filePath;
            }

            if ($data) {
                GrowthSectionContent::where('id', $data->id)->update(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'sub_title' => $request->sub_title ?? $defaults['sub_title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            } else {
                GrowthSectionContent::create(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'sub_title' => $request->sub_title ?? $defaults['sub_title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating growth section: ', [
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

    public function shippingSectionContent()
    {
        $shippingSection = ShippingSectionContent::first();
        return view('admin.homePageContents.shippingSection',compact('shippingSection'));
    }
    public function updateShippingSectionContent(Request $request)
    {
        try
        {
            $data = ShippingSectionContent::first();

            $defaults = [
                'title' => $data ? $data->title : null,
                'description' => $data ? $data->description : null,
                'img' => $data ? $data->img : null,
                'img2' => $data ? $data->img2 : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('img')) {
                $filePath = $this->storeShippingFile($request->file('img'));
                $img_url = $filePath;
            }

            $img_url2 = '';
            if ($request->hasFile('img2')) {
                $filePath = $this->storeShippingFile($request->file('img2'));
                $img_url2 = $filePath;
            }

            if ($data) {
                ShippingSectionContent::where('id', $data->id)->update(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                        'img2' => $request->hasFile('img2') ? $img_url2 : $defaults['img2'],
                    ]
                );
            } else {
                ShippingSectionContent::create(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                        'img2' => $request->hasFile('img2') ? $img_url2 : $defaults['img2'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating shipping section: ', [
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

    public function courierSectionContent()
    {
        $courierSection = CourierSectionContent::first();
        return view('admin.homePageContents.courierSection',compact('courierSection'));
    }
    public function updateCourierSectionContent(Request $request)
    {
        try
        {
            $data = CourierSectionContent::first();

            $defaults = [
                'title' => $data ? $data->title : null,
                'description' => $data ? $data->description : null,
                'video' => $data ? $data->video : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('video')) {
                $filePath = $this->storeCourierVideo($request->file('video'));
                $img_url = $filePath;
            }

            if ($data) {
                CourierSectionContent::where('id', $data->id)->update(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'video' => $request->hasFile('video') ? $img_url : $defaults['video'],
                    ]
                );
            } else {
                CourierSectionContent::create(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'video' => $request->hasFile('video') ? $img_url : $defaults['video'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating courier section: ', [
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
    public function deliverySectionContent()
    {
        $deliverySection = DeliverySectionContent::first();
        return view('admin.homePageContents.deliverySection',compact('deliverySection'));
    }
    public function updateDeliverySectionContent(Request $request)
    {
        try
        {
            $data = DeliverySectionContent::first();

            $defaults = [
                'title' => $data ? $data->title : null,
                'description' => $data ? $data->description : null,
                'img' => $data ? $data->img : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('img')) {
                $filePath = $this->storeDeliveryFile($request->file('img'));
                $img_url = $filePath;
            }

            if ($data) {
                DeliverySectionContent::where('id', $data->id)->update(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            } else {
                DeliverySectionContent::create(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating delivery section: ', [
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
    public function contactSectionContent()
    {
        $contactSection = ContactSectionContent::first();
        return view('admin.homePageContents.contactSection',compact('contactSection'));
    }
    public function updateContactSectionContent(Request $request)
    {
        try
        {
            $data = ContactSectionContent::first();

            $defaults = [
                'description' => $data ? $data->description : null,
            ];

            if ($data) {
                ContactSectionContent::where('id', $data->id)->update(
                    [
                        'description' => $request->description ?? $defaults['description'],
                    ]
                );
            } else {
                ContactSectionContent::create(
                    [
                        'description' => $request->description ?? $defaults['description'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating contact section: ', [
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

    public function setOffVideoSectionContent()
    {
        $videoSection = SetOffVideoSectionContent::first();
        return view('admin.setOffPageContents.videoSection',compact('videoSection'));
    }
    public function updateSetOffVideoSectionContent(Request $request)
    {
        try
        {
            $data = SetOffVideoSectionContent::first();

            $defaults = [
                'video' => $data ? $data->video : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('video')) {
                $filePath = $this->storeFile($request->file('video'));
                $img_url = $filePath;
            }

            if ($data) {
                SetOffVideoSectionContent::where('id', $data->id)->update(
                    [
                        'video' => $request->hasFile('video') ? $img_url : $defaults['video'],
                    ]
                );
            } else {
                SetOffVideoSectionContent::create(
                    [
                        'video' => $request->hasFile('video') ? $img_url : $defaults['video'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating video section: ', [
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
    public function ruleSectionContent()
    {
        $growthSection = CreditRule::first();
        return view('admin.homePageContents.creditRulesSection',compact('growthSection'));
    }
    public function updateRuleSectionContent(Request $request)
    {
        try
        {
            $data = CreditRule::first();

            $defaults = [
                'description' => $data ? $data->description : null,
            ];

            if ($data) {
                CreditRule::where('id', $data->id)->update(
                    [
                        'description' => $request->description ?? $defaults['description'],
                    ]
                );
            } else {
                CreditRule::create(
                    [
                        'description' => $request->description ?? $defaults['description'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating credit rules section: ', [
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
    public function globalSectionContent()
    {
        $globalSection = GlobalInfo::first();
        return view('admin.homePageContents.globalSection',compact('globalSection'));
    }
    public function updateGlobalSectionContent(Request $request)
    {
        try
        {
            $data = GlobalInfo::first();

            $defaults = [
                'title' => $data ? $data->title : null,
                'description' => $data ? $data->description : null,
                'img' => $data ? $data->img : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('img')) {
                $filePath = $this->storeDeliveryFile($request->file('img'));
                $img_url = $filePath;
            }

            if ($data) {
                GlobalInfo::where('id', $data->id)->update(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            } else {
                GlobalInfo::create(
                    [
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating global section: ', [
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
    public function cashinImgChange()
    {
        $cashInImg = CashInImg::first();
        return view('admin.homePageContents.cashInImg',compact('cashInImg'));
    }
    public function updateCashinImgChange(Request $request)
    {
        try
        {
            $data = CashInImg::first();

            $defaults = [
                'img' => $data ? $data->img : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('img')) {
                $filePath = $this->storeDeliveryFile($request->file('img'));
                $img_url = $filePath;
            }

            if ($data) {
                CashInImg::where('id', $data->id)->update(
                    [
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            } else {
                CashInImg::create(
                    [
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating cashinimg section: ', [
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
    public function signUpContent()
    {
        $data = SignUpContent::first();
        return view('admin.homePageContents.signUpSection',compact('data'));
    }
    public function updateSignUpContent(Request $request)
    {
        try
        {
            $data = SignUpContent::first();

            $defaults = [
                'title' => $data ? $data->title : null,
            ];

            if ($data) {
                SignUpContent::where('id', $data->id)->update(
                    [
                        'title' => $request->title ?? $defaults['title'],
                    ]
                );
            } else {
                SignUpContent::create(
                    [
                        'title' => $request->title ?? $defaults['title'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating SignUpContent section: ', [
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
    public function giftBoxContent()
    {
        $data = GiftBoxContent::first();
        return view('admin.homePageContents.giftBoxSection',compact('data'));
    }
    public function updateGiftBoxContent(Request $request)
    {
        try
        {
            $data = GiftBoxContent::first();

            $defaults = [
                'title' => $data ? $data->title : null,
            ];

            if ($data) {
                GiftBoxContent::where('id', $data->id)->update(
                    [
                        'title' => $request->title ?? $defaults['title'],
                    ]
                );
            } else {
                GiftBoxContent::create(
                    [
                        'title' => $request->title ?? $defaults['title'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating GiftBoxContent section: ', [
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
        $filePath = 'uploads/hero'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('banner_', true) . '.' . $file->getClientOriginalExtension();

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
        $filePath = 'uploads/logo'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('logo_', true) . '.' . $file->getClientOriginalExtension();

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
        if (!empty($data->company_logo)) { # ensure from database
            $oldFilePath = public_path($data->company_logo); // Use without prepending $filePath
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // Delete the old file
                return true;
            } else {
                Log::warning('Old file not found for deletion', ['path' => $oldFilePath]);
                return false;
            }
        }
    }
    private function storeVideo($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/hero'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('video_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function storeGrowthFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/growth'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('growth_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function storeShippingFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/shipping'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('shipping_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function storeCourierVideo($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/courier'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('video_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function storeDeliveryFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/delivery'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('delivery_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
}
