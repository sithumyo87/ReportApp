<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->paginate = 10;
        $this->middleware('permission:permission-index|permission-create|permission-edit|permission-delete', ['only' => ['index','store']]);
        $this->middleware('permission:permission-create', ['only' => ['create','store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = Permission::where('id','>',0);
        if($request->name != ''){
            $permissions = $permissions->where('name', 'like', '%'.$request->name.'%');
        }
        $data['permissions']    = $permissions->orderBy('id','DESC')->paginate($this->paginate);
        $data['page']           = ($request->input('page', 1) - 1) * $this->paginate;
        $data['search']         = $request->all();
        return view('setting.permission.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('setting.permission.create');
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
            'name' => 'required|unique:permissions,name',
        ]);
        $permission = Permission::create(['name' => $request->input('name')]);
        return redirect()->route('setting.permission.index')
        ->with('success','Permission is created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['permission'] = Permission::findOrFail($id);
        return view('setting.permission.edit', $data);
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
        $permission = Permission::findOrFail($id);
        if($permission->name != $request->name){
            $this->validate($request, [
                'name' => 'required|unique:permissions,name',
            ]);
        }
        $permission->name = $request->input('name');
        $permission->save();
        return redirect()->route('setting.permission.index')
        ->with('success','Permission is edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("permissions")->where('id',$id)->delete();
        return redirect()->route('setting.permission.index')
        ->with('success','Permission is deleted successfully');
    }
}
