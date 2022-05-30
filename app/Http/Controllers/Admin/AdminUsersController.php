<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/users');
    }

    private function getUserButtons($userId) {
        $editButton =
            '<a href="'.route('users.edit', ['user' => $userId]).'" id="edit-'.$userId.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';

        $softDeleteButton =
            '<a href="'.route('users.destroy', ['user' => $userId]).'" title="soft delete" id="delete-'.$userId.'" class="ajax btn btn-sm btn-warning delete-btn"><i class="fa fa-trash"></i> Delete</a>&nbsp;';

        $forceDeleteButton =
            '<a href="'.route('users.destroy', ['user' => $userId]).'?hard=1" title="hard delete" id="forceDelete-'.$userId.'" class="ajax btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i> Force Delete</a>';

        // Per funzionalità restore
        $user = User::withTrashed()->findOrFail($userId);
        if( $user->deleted_at ) {
            $softDeleteButton = '<a href="'.route('admin.userrestore', ['user' => $userId]).'" title="restore" id="restore-'.$userId.'" class="ajax btn btn-sm btn-success"><i class="fa fa-trash-restore"></i> Restore</a>&nbsp;';
        }

        return $editButton.$softDeleteButton.$forceDeleteButton;
    }

    public function getUsers() {
        $users = User::select(['id','name', 'email', 'user_role', 'created_at', 'deleted_at'])->latest()
                ->withTrashed()
                ->get();
        $result = DataTables::of($users)
                    ->addColumn('action', function($user) {
                        return $this->getUserButtons($user->id);
                    })
                    ->editColumn('created_at', function($user) {
                        return $user->created_at->format('d/m/Y H:i');
                    })
                    ->editColumn('deleted_at', function($user) {
                        return $user->deleted_at ? $user->deleted_at->format('d/m/Y H:i') : '';
                    })
                    ->make(true);
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        return view('admin.edituser', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        $user = new User();
        $user->fill($request->only(['name', 'email', 'user_role']));

        $user->password = Hash::make($request->email);

        $res = $user->save();

        return redirect()->route('users.index');
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        return view('admin.edituser', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserFormRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_role = $request->user_role;
        $res = $user->save();

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $hard = \request('hard', '');
        $res = $hard ? $user->forceDelete() : $user->delete();

        return ''.$res; // Laravel vuole come risposta una stringa, aggiungiamo quindi '' al return. Metodo moooolto discutibile
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $hard = \request('hard', '');
        $res = $user->restore();

        return ''.$res;
    }
}
