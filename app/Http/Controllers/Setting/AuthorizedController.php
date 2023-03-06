<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Authorizer;

class AuthorizedController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:authorizer-index|authorizer-create|authorizer-edit|authorizer-delete', ['only' => ['index','store']]);
        $this->middleware('permission:authorizer-show', ['only' => ['show']]);
        $this->middleware('permission:authorizer-create', ['only' => ['create','store']]);
        $this->middleware('permission:authorizer-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:authorizer-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Authorizer::where('id','>',0)->orderBy('id','DESC')->paginate(5);
        return view('setting.authorizer.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('setting.authorizer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'authorized_name' => 'required',
        ]);

        if($request->hasFile('file')){
            $request->validate([
                'file' => 'required|mimes:png,jpg,jpeg|max:10240'
              ]);
            $fileNameToStore = $request->file->getClientOriginalName();
            $request->file->move(public_path('authorizers/'), $fileNameToStore);
            $storedFileName= 'authorizers/'.$fileNameToStore;
        }else{
            $storedFileName = null;
        }
        $input = Authorizer::create([
                'authorized_name'=>$request->authorized_name,
                'file_name'=>$storedFileName,
            ]);
        return redirect()->route('setting.authorizer.index')
                        ->with('success','Authorizer created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $authorizer = Authorizer::findOrFail($id);
        return view('setting.authorizer.edit',compact('authorizer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $authorizer = Authorizer::findOrFail($id);
        $this->validate($request, [
            'authorized_name' => 'required',
        ]);

        if($request->hasFile('file')){
            $this->validate($request, [
                'file' => 'image|mimes:jpeg,png,jpg|max:10240',
            ]);
            $fileNameToStore = $request->file->getClientOriginalName();
            $request->file->move(public_path('authorizers/'), $fileNameToStore);
            $storedFileName= 'authorizers/'.$fileNameToStore;
        }else{
            $storedFileName = $authorizer->file_name;
        }

        $authorizer->authorized_name            = $request->input('authorized_name');
        $authorizer->file_name            = $storedFileName;
        $authorizer->save();
    
        return redirect()->route('setting.authorizer.index')
                        ->with('success','Authorizer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authorizer = Authorizer::findOrFail($id);
        $authorizer->destroy($id);
        return redirect()->route('setting.authorizer.index')
                        ->with('success','Authorizer deleted successfully');
    }
}
