<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\ProductCategeory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
class ProductCategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('product-category.view') && !auth()->user()->can('product-category.create')) {
            abort(403, 'Unauthorized action.');
        }
        $categeory_list = ProductCategeory::all();
        $product_category = ProductCategeory::get();
        $product_category = $product_category->transform(function ($item) {
            $item->action = 1;
            if (!empty($item->icon)) {
                $image_url = asset(Storage::url(config('constants.category_img_path') . '/' . $item->icon));
            } else {
                $image_url = asset('/img/default.png');
            }
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'parent' => $item->parent->name ?? '',
                'image' => $image_url,
                'status' => $item->status,
                'action' => 1
            ];
        });

        if (request()->ajax()) {
            return Datatables::of($product_category)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn('image', function ($row) {
                return '<img style="border-radius: 5px; object-fit: cover;" class="w-8 h-8" src="'.$row['image'].'">';
            })

            ->editColumn('status', function ($row) {
                $span = '<span class="btn btn-danger btn-sm" style="width:80px;color:#fff;">In Active</span>';
                if($row['status'] == 'Active')
                {
                    $span = '<span class="btn btn-success btn-sm" style="width:50px;color:#fff;">Active</span>';
                }
                return $span;
            })
            ->rawColumns(['action', 'image','status'])
            ->make(true);
        }
        return view('rest.category.index', compact('categeory_list'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('product-category.create')) {
            abort(403, 'Unauthorized action.');
        }
        $product_category = new ProductCategeory();
        $product_category->code = $request->code;
        $product_category->name = $request->name;
        $product_category->parent_id = $request->parent;

        // if ($request->SelectParent) {
        //     $product_category->parent_id = $request->SelectParent;
        // }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.category_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $product_category->icon = $new_file_name;
            }
        }

        $product_category->status = $request->has('status') ? 'Active' : 'Inactive';
        $product_category->save();

        return redirect()->route('categeory.index')
            ->with('success', 'product_category successfully Created!!');
        
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('product-category.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;
        $product_category = ProductCategeory::find($id);
        $product_category->code = $request->code;
        $product_category->name = $request->name;
        $product_category->parent_id = $request->parent;


        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.category_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $product_category->icon = $new_file_name;
            }
        }


        $product_category->status = $request->has('status') ? 'Active' : 'Inactive';
        $product_category->save();
        return redirect()->route('categeory.index')
            ->with('success', 'product_category successfully Updated!!');
    }


    public function delete(Request $request)
    {
        if (!auth()->user()->can('product-category.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $product_category =  ProductCategeory::whereIn('id', $ids)->delete();;

                $output = [
                    'success' => true,
                    'msg' => __("Deleted Success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    public function show($id)
    {
        $product_category = ProductCategeory::find($id);
        if (!empty($product_category->icon)) {
            $image_url = asset(Storage::url(config('constants.category_img_path') . '/' . $product_category->icon));
        } else {
            $image_url = asset('/img/default.png');
        }
        $product_category->setAttribute('icon', $image_url);
        return $product_category;
    }
}
