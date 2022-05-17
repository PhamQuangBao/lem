<?php

namespace App\Repositories;

use App\Repositories\BaseRepositoryInterface;
interface ProfileRepositoryInterface extends BaseRepositoryInterface
{
    public function getJobs();
}