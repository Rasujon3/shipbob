<?php

namespace App\Http\Controllers;

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

class ProductController extends Controller
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

               $products = Product::select('*')->latest();

                    return Datatables::of($products)
                        ->addIndexColumn()

                        ->addColumn('name', function($row){
                            return $row->name;
                        })

                        ->addColumn('price', function($row){
                            return $row->price;
                        })

                        ->addColumn('description', function($row){
                            return $row->description;
                        })

                        ->addColumn('commission', function($row){
                            return $row->commission;
                        })

                        ->addColumn('action', function($row){

                           $btn = "";
                           $btn .= '&nbsp;';

                           $btn .= ' <a href="'.route('products.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-product" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-product action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        // search customization
                        ->filter(function ($query) use ($request) {
                            if ($request->has('search') && $request->search['value'] != '') {
                                $searchValue = $request->search['value'];
                                $query->where(function($q) use ($searchValue) {
                                    $q->where('name', 'like', "%{$searchValue}%")
                                        ->orWhere('price', 'like', "%{$searchValue}%")
                                        ->orWhere('commission', 'like', "%{$searchValue}%");
                                });
                            }
                        })
                        ->rawColumns(['name','price','description','commission','action'])
                        ->make(true);
            }

            return view('admin.products.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.products.create');
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

            return redirect()->route('products.index')->with($notification);

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
    public function show(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }
    public function edit(Product $product)
    {
        //
    }
    public function update(UpdateProductRequest $request, Product $product)
    {
        try
        {
            // Handle file upload
            $path = $product->file;
            if ($request->hasFile('file')) {
                $filePath = $this->updateFile($request->file('file'), $product);
                $path = $filePath ?? '';
            }

            $product->name = $request->name ?? $product->name;
            $product->price = $request->price ?? $product->price;
            $product->description = $request->description ?? $product->description;
            $product->commission = $request->commission ?? $product->commission;
            $product->file = $path;
            $product->save();

            $notification=array(
                'message'=>'Successfully the product has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('products.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating product: ', [
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
