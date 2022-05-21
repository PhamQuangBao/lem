<?php
namespace App\Repositories;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserRoles();

    public function getAllUsers();

    public function findUser($id);

    public function deleteUser($id);

    public function findUserByEmail($email);

    public function getRolesIDByUserID($userID, $roleID);
}