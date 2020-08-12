<?php

namespace App\Http\Controllers\api;


use Illuminate\Http\Request;
use App\Http\Controllers\api\ResponseController as ResponseController;
use App\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\BenchTrait;
use DB;

class RoleController extends ResponseController
{
    use BenchTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         /* $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]); */
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::with('getPermissions')->get();
        return $this->sendResponseData($roles, $request);
    }

    /**
     * Display a listing of the resource for guest.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRoles(Request $request)
    {
        $roles = Role::all();
        return $this->sendResponseData($roles, $request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* $permission = Permission::get();
        return view('roles.create',compact('permission')); */
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
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        // Test scripts' execution time
        /* $this->start('bench_1');

        $role = Role::create(['name' => $request->input('name')]);

        print '<pre>';
        print_r($role);

        $this->end('bench_1');

        print 'First benchmark had taken: '.$this->calculate('bench_1');
        exit; */

        $role = Role::create(['name' => $request->input('name')]);

        $permissionAry = json_decode($request->input('permission'), true);
        $role->syncPermissions($permissionAry);

        if ($role) {
            $id = $role->id;
            $message = "Role created successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Role creation is not successful.";
            return $this->sendError($error, 401);
        }
        
        /* return response()
            ->json(['message' => 'Role created successfully', 'status_code' => 200])
            ->withCallback($request->input('callback')); */
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Int $id)
    {
        $roles = Role::with('getPermissions')
        ->where('id', $id)
        ->get();
        return $this->sendResponseData($roles, $request);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id',$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $data = compact('role','permission','rolePermissions');
        return response()
            ->json($data)
            ->withCallback($request->input('callback'));
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
            'name' => 'required',
            'permission' => 'required',
        ]);


        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $permissionAry = json_decode($request->input('permission'), true);
        $role->syncPermissions($permissionAry);

        if ($role) {
            $message = "Role updated successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Updating role is not successful.";
            return $this->sendError($error, 401);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $role = Role::where('id', $id)
        ->delete();

        if ($role) {
            $message = "Role deleted successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Deleting role is not successful.";
            return $this->sendError($error, 401);
        }
    }
}