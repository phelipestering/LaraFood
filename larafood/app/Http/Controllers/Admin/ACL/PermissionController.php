<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Requests\StoreUpdatepermission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $repository;

    public function __construct(Permission $permission)
    {
        $this->repository = $permission;
    }

    public function index()
    {
        $permissions = $this->repository->paginate();

        return view('admin.pages.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.pages.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdatepermission $request)
    {
        $this->repository->create($request->all());

        return redirect()->route('permissions.index');
    }

    /**
     * Display the specified resource.
     *
     *@param  App\Http\Requests\StoreUpdatepermission $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$permission = $this->repository->find($id)){
            return redirect()->back();
        }

        return view('admin.pages.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$permission = $this->repository->find($id)){
            return redirect()->back();
        }

        return view('admin.pages.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdatepermission $request, $id)
    {
        if(!$permission = $this->repository->find($id)){
            return redirect()->back();
        }

        $permission->update($request->all());

        return redirect()->route('permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        if(!$permission = $this->repository->find($id)){
            return redirect()->back();
        }

        $permission->delete();

        return redirect()->route('permissions.index', $permission->id)
                            ->with('message', 'Registro Deletado com Sucesso');
    }

    /**
     * Search method
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */

    public function search(Request $request)
    {
        $filters = $request->only('filter');

        $permissions = $this->repository
                            ->where(function ($query) use ($request){
                                if($request->filter)
                                {
                                    $query->where('name', $request->filter)
                                           ->orWhere('description', 'LIKE',"%{$request->filter}%");
                                }
                            })
                            ->paginate();

    return view('admin.pages.permissions.index', compact('permissions', 'filters'));
    }
}
