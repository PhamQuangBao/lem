<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepositoryInterface;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function add()
    {
        $userRoles = $this->userRepository->getUserRoles();
        return view('admin.users.add', [
            'title' => 'Add new User',
            'userRoles' => $userRoles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->all();
        $data['password']=bcrypt($data['password']);
        $user =  $this->userRepository->StoreUser($data);

        $userRoles = $this->userRepository->getUserRoles();
        if ($user == 'true') {
            return redirect()->back()->with([
                'title' => 'Add new User',
                'userRoles' => $userRoles,
                'success' => 'Add new User is successfully!',
            ]);
        } else {
            return redirect()->back()->with([
                'title' => 'Add new User',
                'userRoles' => $userRoles,
                'error' => 'Add new User has something wrong!',
            ]);
        }
    }

    public function list()
    {
        $users = $this->userRepository->getAllUsers();
        
        return view('admin.users.list', [
            'title' => 'User list',
            'users' => $users,
        ]);
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
        $user = $this->userRepository->findUser($id);
        $userRoleUsers = $this->userRepository->findUserRoleUsers($id);
        $userRoles = $this->userRepository->getUserRoles();
        if ($user) {
            return view('admin.users.edit', [
                'title' => 'edit user',
                'userRoleUsers' => $userRoleUsers,
                'userRoles' => $userRoles,
                'user' => $user,
            ]);
        } else {
            return redirect()->back()->with('error', 'User not found!');;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->all();
        //check have password and have checkbox
        if(isset($data['password']) && $request->editPassword == 'on') {
            $dataUser = $request->only(['name','password']);
            $dataUser['password'] = bcrypt($data['password']);
        } else {
            $dataUser = $request->only(['name']);
        }
        //change isActive
        if($request->isActive == 'on'){
            $dataUser['isActive'] = true;
        }
        else{
            $dataUser['isActive'] = false;
        }

        $user = $this->userRepository->find($id);
        $userRoleUsers = $this->userRepository->findUserRoleUsers($id);

        if($user && $userRoleUsers){
            $userUpdate = $user->update($dataUser);
            $userRoleUsersUpdate = $userRoleUsers->update($request->only(['role_id']));
            if ($userUpdate && $userRoleUsersUpdate) {
                return redirect()->back()->with('success', 'Update user detail Success!');
            } else {
                return redirect()->back()->with('error', 'Update user detail has something wrong!!');
            }
        }
        else{
            return redirect()->back()->with('error', 'User not found!');
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->deleteUser($id);
        if ($user) {
            return redirect()->back()->with(['success' => 'Delete User is successfully!']);
        }
        return redirect()->back()->with(['error' => 'The User has been deleted or has something wrong!']);
    }
}
