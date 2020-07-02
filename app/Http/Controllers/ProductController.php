<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
  
        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'image'=> 'required|image|max:2048'
        ]);
        $image=$request->file('image');
        $new_name= rand() . '.'. $image->getClientOriginalExtension();
        $image->move(public_path('images'),$new_name);
        $form_data=array(
          'name'=> $request->name,
          'detail'=> $request->detail,  //aise image kyun kiya???
           'image'=> $new_name

        );
        Product::create($form_data);
      // Product::create($request->all());

   
        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show',compact('product'));
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit',compact('product'));
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {   $image_name=$request->hidden_image;
        $image=$request->file('image');
        if($image !=""){
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'image'=> 'required|image|max:2048'
        ]);
        $image_name= rand() . '.'. $image->getClientOriginalExtension();
        $image->move(public_path('images'),$image_name);
       
        }
        else{
            $request->validate([
                'name' => 'required',
                'detail' => 'required',
            ]);
        }
        $form_data=array(
            'name'=> $request->name,
            'detail'=> $request->detail,  //aise image kyun kiya???
             'image'=> $image_name
  
          );
          $product->update($form_data);
       // $product->update($request->all());
  
        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
  
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}