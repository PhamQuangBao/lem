<?php

namespace App\Repositories;

use App\Repositories\BaseRepositoryInterface;
interface JobRepositoryInterface extends BaseRepositoryInterface
{
  public function getJobs();

  public function getAllBranch();

  public function getJobStatuses();

  public function createJob($attributes = []);

  public function findLastJob($year);

  public function getJobWithStatus();

  public function findJob($id);

  public function updateJob($id, $attributes = []);

  public function getJobWithBranchOnAddProfile();

  public function getJobWithStatusOpen();
}