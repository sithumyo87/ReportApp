<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Authorizer;

class AuthorizedController extends Controller
{
    public function index(Request $request)
    {
        $data = Authorizer::where('id','>',0)->orderBy('id','DESC')->paginate(5);
        return view('Setting.authorizer.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
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
            'Currency_name' => 'required',
            'symbol' => 'required',
        ]);
        
        $input = $request->all();
        $authorizer = Authorizer::create($input);
        return redirect()->route('setting.authorizer.index')
                        ->with('success','Currency created successfully');
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
        $authorizer = Authorizer::find($id);
        return view('Setting.authorizer.edit',compact('currency'));
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
        $this->validate($request, [
            'Currency_name' => 'required',
            'symbol' => 'required',
        ]);
    
        $input = $request->all();

        $authorizer = Authorizer::find($id);
        $authorizer->update($input);
    
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
        $authorizer = Authorizer::find($id);
        return redirect()->route('setting.authorizer.index')
                        ->with('success','Authorizer deleted successfully');
    }
}
