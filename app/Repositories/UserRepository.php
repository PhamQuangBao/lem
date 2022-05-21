<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Users;
use App\Models\UserRoleUsers;
use App\Models\UserRoles;
use Exception;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    //Get Model to BaseRepository 
    public function getModel()
    {
        return Users::class;
    }

    /**
     * Get All User Roles
     * @param
     * @return mixed
     */
    public function getUserRoles()
    {
        return UserRoles::all();
    }

    /**
     * Insert User with User Roles
     * @param
     * @return mixed
     */
    public function StoreUser($data)
    {
        DB::beginTransaction();
        // try {
            Users::create($data);
            //get UserID 
            if (Users::Orderby('id', 'DESC')->first()) {
                $userID = (int) Users::Orderby('id', 'DESC')->first()->id;
            } else {
                $userID = 1;
            }
            $dataUserRoleUsers = [
                'user_id' => $userID,
                'role_id' => $data['user_roles_id'],
            ];

            //check user id had to user role users.
            //If have user then update Else insert Role
            $UserRoleUsers = UserRoleUsers::where('user_id', $userID)->first();
            //dd($UserRoleUsers);
            if ($UserRoleUsers) {
                $UserRoleUsers->user_id = $userID;
                $UserRoleUsers->role_id = $data['user_roles_id'];
                $UserRoleUsers->save();
            } else {
                UserRoleUsers::create($dataUserRoleUsers);
            }
            DB::commit();
            return true;
        // } catch (\Exception $e) {
        //     return $e->getMessage();
        //     DB::rollback();
        // } finally {
        //     DB::disconnect();
        // }
    }

    /**
     * get Role by UserID
     * @param userID, array roleID
     * @return bool
     */
    public function getRolesIDByUserID($userID, $roleID)
    {
        $userRoles = UserRoles::join('user_role_users','user_roles.id', 'user_role_users.role_id')
        ->where('user_role_users.user_id', $userID)
        ->get();

        //check user have role ID, if have RoleID then true
        foreach($userRoles as $userRole) {
            if($roleID == in_array($userRole->role_id, $roleID))
                return true;
        }
        return false;
    }

    /**
     * get All users
     * 
     * @return App\Models\Users
     */
    public function getAllUsers()
    {
        return Users::orderBy('created_at', 'DESC')->get();
    }

    /**
     * find Users by id
     * @param $id
     * @return App\Models\Users;
     */
    public function findUser($id)
    {
        $result = Users::find($id);

        return $result;
    }

    /**
     * find UserRoleUsers by id
     * @param $id
     * @return App\Models\UserRoleUsers;
     */
    public function findUserRoleUsers($id)
    {
        $result = UserRoleUsers::find($id);

        return $result;
    }

    public function deleteUser($id)
    {
        $result = $this->findUser($id);
            if ($result) {
                $result->update(['isActive' => false]);

                return true;
            }
        return false;

    }

    /**
     * find Users by email
     * @param $email
     * @return App\Models\Users;
     */
    public function findUserByEmail($email)
    {
        $result = Users::where('email', $email)->first();

        return $result;
    }
}
