<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\LoginPageContent;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function settings()
    {
        $setting = Setting::first();
//        dd($setting->is_site_active);
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
                'daily_task_limit' => $data ? $data->daily_task_limit : null,
                'rtt_trial_balance' => $data ? $data->rtt_trial_balance : 0,
                'is_site_active' => $data ? $data->is_site_active : 'Active',
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
                        'daily_task_limit' => $request->daily_task_limit ?? $defaults['daily_task_limit'],
                        'rtt_trial_balance' => $request->rtt_trial_balance ?? $defaults['rtt_trial_balance'],
                        'is_site_active' => $request->is_site_active ?? $defaults['is_site_active'],
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
                        'daily_task_limit' => $request->daily_task_limit ?? $defaults['daily_task_limit'],
                        'rtt_trial_balance' => $request->rtt_trial_balance ?? $defaults['rtt_trial_balance'],
                        'is_site_active' => $request->is_site_active ?? $defaults['is_site_active'],
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

    public function aboutUs()
    {
        $aboutUs = AboutUs::first();
        return view('admin.settings.about_us',compact('aboutUs'));
    }
    public function storeAboutUs(Request $request)
    {
        try
        {
            $data = AboutUs::first();

            $defaults = [
                'user_agreement' => $data ? $data->user_agreement : null,
                'privacy' => $data ? $data->privacy : null,
            ];

            if ($data) {
                AboutUs::where('id', $data->id)->update(
                    [
                        'user_agreement' => trim($request->user_agreement) ?? $defaults['user_agreement'],
                        'privacy' => trim($request->privacy) ?? $defaults['privacy'],
                    ]
                );
            } else {
                AboutUs::create(
                    [
                        'user_agreement' => trim($request->user_agreement) ?? $defaults['user_agreement'],
                        'privacy' => trim($request->privacy) ?? $defaults['privacy'],
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
            Log::error('Error in updating about us: ', [
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
    public function loginPageContent()
    {
        $loginPageContent = LoginPageContent::first();
        return view('admin.settings.loginPageContents',compact('loginPageContent'));
    }
    public function updateLoginPageContent(Request $request)
    {
        try
        {
            $data = LoginPageContent::first();

            $defaults = [
                'name' => $data ? $data->name : null,
                'title' => $data ? $data->title : null,
                'description' => $data ? $data->description : null,
                'img' => $data ? $data->img : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('img')) {
                $filePath = $this->storeLoginFile($request->file('img'));
                $img_url = $filePath;
                $this->deleteLoginOldFile($data);
            }


            if ($data) {
                LoginPageContent::where('id', $data->id)->update(
                    [
                        'name' => $request->name ?? $defaults['name'],
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            } else {
                LoginPageContent::create(
                    [
                        'name' => $request->name ?? $defaults['name'],
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
            Log::error('Error in updating login page content: ', [
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
        $filePath = 'uploads/logo'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('logo_', true) . '.' . $file->getClientOriginalExtension();

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
    private function storeLoginFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/login'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('login_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function deleteLoginOldFile($data)
    {
        // TODO: ensure from database
        if (!empty($data->img)) { # ensure from database
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
