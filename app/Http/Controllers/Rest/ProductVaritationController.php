<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class ProductVaritationController extends Controller
{

    public function index()
    {
        if (!auth()->user()->can('product-variation.view') && !auth()->user()->can('product-variation.create')) {
            abort(403, 'Unauthorized action.');
        }
        $Product = Product::all();
        $productVariation = ProductVariation::select('*')
        ->get();
        $productVariation->transform(function ($item) {
            $item->action = 1;
            $decimal_value = 'No';
            if($item->decimal_value == 1)
            {
                $decimal_value = 'Yes';
            }
            return [
                'id' => $item->id,
                'type' => $item->type,
                'name' => $item->name,
                'decimal' => $decimal_value,
                'decimal_value' => $item->decimal_value,
                'status' => $item->status,
                'checked' => $item->status == 'Active' ? 'checked' : '',
                'action' => 1
            ];
        });

        if (request()->ajax()) {
            return Datatables::of($productVariation)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn('decimal', function ($row) {
                $span = '<span class="btn btn-danger btn-sm" style="width:50px;color:#fff;">No</span>';
                if($row['decimal'] == 'Yes')
                {
                    $span = '<span class="btn btn-success btn-sm" style="width:50px;color:#fff;">Yes</span>';
                }
                return $span;
            })

            ->editColumn('status', function ($row) {
                $span = '<span class="btn btn-danger btn-sm" style="width:80px;color:#fff;">In Active</span>';
                if($row['status'] == 'Active')
                {
                    $span = '<span class="btn btn-success btn-sm" style="width:50px;color:#fff;">Active</span>';
                }
                return $span;
            })
            ->rawColumns(['action', 'decimal','status'])
            ->make(true);
        }
        return view('rest.product_variation.index',compact('Product'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('product-variation.create')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id

        if (!isset($id)) {

            $productVariation = new ProductVariation();
            $productVariation->type = $request->type;
            $productVariation->name = $request->name;
            $productVariation->decimal_value = $request->decimal_value ? 1 : 0;
            $productVariation->status = $request->has('status') ? 'Active' : 'Inactive';
            $productVariation->save();

            return redirect()->route('product_variation.index')
                ->with('success', 'productVariation successfully Created!!');
        } else {

            $productVariation = ProductVariation::find($id);
            $productVariation->type = $request->type;
            $productVariation->name = $request->name;
            $productVariation->decimal_value = $request->decimal_value ? 1 : 0;
            $productVariation->status = $request->has('status') ? 'Active' : 'Inactive';
            $productVariation->save();
            return redirect()->route('product_variation.index')
                ->with('success', 'productVariation successfully Updated!!');
        }
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('product-variation.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id
        $productVariation = ProductVariation::find($id);
        $productVariation->type = $request->type;
        $productVariation->name = $request->name;
        $productVariation->decimal_value = $request->decimal_value ? 1 : 0;
        $productVariation->status = $request->has('status') ? 'Active' : 'Inactive';
        $productVariation->save();
        return redirect()->route('product_variation.index')
                ->with('success', 'productVariation successfully Updated!!');
    }


    public function delete(Request $request)
    {
        if (!auth()->user()->can('product-variation.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $productVariation =  ProductVariation::whereIn('id', $ids)->delete();;

                $output = ['success' => true,
                            'msg' => __("Deleted Success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    public function show($id)
    {
        $productVariation = ProductVariation::find($id);

        return $productVariation;
    }

}
