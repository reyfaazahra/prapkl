<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Str;
use Alert;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::latest()->get();

        $title = 'Hapus Data!';
        $text  = "Apakah anda yakin?";
        confirmDelete($title, $text);

        return view('backend.product.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $category = Category::all();
       return view('backend.product.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:products',
            'category_id' =>'required',
            'price'=>'required|numeric',
            'description' => 'required',
            'stock' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,png',
        ]);

        $product       = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name, '-');
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        if ($request->hasFile('image')) {
            $file   =$request->file('image');
            $randomName     =Str::random(20).'.'.$file->getClientOriginalExtension();
            $path           =$file->storeAs('products',$randomName,'public');
            $product->image = $path;
        }
        $product->save();
        toast('Data Berhasil disimpan', 'success');
        return redirect()->route('backend.product.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('backend.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $product  = Product::findOrFail($id);
        $category = Category::all();
        return view('backend.product.edit', compact('product', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'        => 'required|unique:categories',
            'category_id' => 'required',
            'price'       => 'required|numeric',
            'description' => 'required',
            'stock'       => 'required|numeric',
        ]);

        $product              = Product::findOrFail($id);
        $product->name        = $request->name;
        $product->slug        = Str::slug($request->name, '-');
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->price       = $request->price;
        $product->stock       = $request->stock;

        if ($request->hasFile('image')) {
        // menghapus foto lama
        Storage::disk('public')->delete($product->image);

        // upload foto baru
        $file       = $request->file('image');
        $randomName = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path       = $file->storeAs('products', $randomName, 'public');
        // memasukan nama image nya ke database
        $product->image = $path;
         }


        $product->save();
        toast('Data berhasil di edit', 'success');
        return redirect()->route('backend.product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        toast('Data Berhasil di hapus', 'success');
        return redirect()->route('backend.product.index');
    }
}