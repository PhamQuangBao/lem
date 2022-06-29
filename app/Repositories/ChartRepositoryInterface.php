<?php
namespace App\Repositories;

use App\Repositories\BaseRepositoryInterface;

interface ChartRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllCountJobByDate();
}