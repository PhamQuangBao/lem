<?php

namespace App\Repositories;

use App\Models\ProfileHistorys;
use App\Repositories\BaseRepository;


class ProfileHistoryRepository extends BaseRepository implements ProfileHistoryRepositoryInterface
{
    //Get Model to BaseRepository 
    public function getModel()
    {
        return \App\Models\ProfileHistorys::class;
    }

    /**
     * example get profile_data
     * $data = $this->profileHistoryRepo->getAll();
     * dd(json_decode($data[0]->profile_data));
     */

    /**
     * find profile history
     * @param email
     * 
     * @return \App\Models\ProfileHistorys
     */
    public function findProfileByEmail($mail)
    {
        $profileHistory = ProfileHistorys::where('mail', '=', $mail)->orderBy('id', 'desc')->first();;
        if ($profileHistory){
            return $profileHistory;
        }
        return false;
    }
}