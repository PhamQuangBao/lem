<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

interface ProfileForEmailRepositoryInterface extends BaseRepositoryInterface
{

    public function storeProfileForEmails($profileForEmails);

    public function getProfileEmailForProfileId($profile_id);

}