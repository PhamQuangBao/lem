<?php

namespace App\Repositories;

use App\Models\Branches;
use App\Models\FormAnswerResponses;
use App\Models\FormQuestionResponses;
use App\Models\Jobs;
use App\Models\JobStatuses;
use App\Models\Profile;

class JobRepository extends BaseRepository implements JobRepositoryInterface
{
  public function getModel()
  {
    return \App\Models\Jobs::class;
  }
  /**
   * Get All Jobs
   * @param
   * @return App\Models\Jobs;
   */
  public function getJobs()
  {
    return Jobs::all();
  }

  /**
   * Get All Branch
   * 
   * @param
   * @return App\Models\Branches
   */
  public function getAllBranch()
  {
      return Branches::all();
  }

  /**
   * Get All Job statuses
   * 
   * @param
   * @return App\Models\JobStatuses
   */
  public function getJobStatuses()
  {
    return JobStatuses::all();
  }

  /**
   * Create new Jobs
   * @param
   * @return App\Models\Jobs;
   */
  public function createJob($attributes = [])
  {
    if ($attributes['job_status_id'] == 2) {
      $attributes['close_date'] = date('Y-m-d H:i:s');
    }
    return Jobs::create($attributes);
  }

  /**
   * Find last Job on year of request date
   * @param $year
   * @return App\Models\Jobs;
   */
  public function findLastJob($year)
  {
    if(Jobs::where('request_date', 'like', '%' . $year . '%')->latest('id')->first()){
      return Jobs::where('request_date', 'like', '%' . $year . '%')->latest('id')->first()->key;
    }
    return 0;
  }

  public function getJobWithStatus()
  {
    return Jobs::with('JobStatuses')->orderBy('request_date', 'DESC')->get();
  }

  /**
   * find Jobs by id
   * @param $id
   * @return App\Models\Jobs;
   */
  public function findJob($id)
  {
    $result = Jobs::find($id);

    return $result;
  }

  /**
   * Update Jobs
   * @param $id 
   * @param $attributes = []
   * if update successfully
   * @return App\Models\Jobs;
   * else 
   * @return false
   */
  public function updateJob($id, $attributes = [])
  {

    $result = $this->findJob($id);
    
    if ($result) {
      $status_id = $result->job_status_id;
      if($status_id == 2){
        if(intval($attributes['job_status_id']) != 2){
          //update null close date when open or pending status job
          $close_date_now = null;
          $attributes['close_date'] = $close_date_now;
          $result->update($attributes);
          return $result;
        }else{
          $result->update($attributes);
          return $result;
        }
      }
      else{
        if(intval($attributes['job_status_id']) == 2){
          //update close data and job
          $close_date_now = date('Y-m-d H:i:s');
          $attributes['close_date'] = $close_date_now;
          $result->update($attributes);
          return $result;
        }
        else{
          $result->update($attributes);
          return $result;
        }
      }
    }
    return false;
  }

  /**
   * Get All jobs with Branch by status open
   * @return App\Models\Jobs
   */
  public function getJobWithBranchOnAddProfile()
  {
    return Jobs::with('Branches')->whereIn('job_status_id', [1])->orderBy('request_date', 'DESC')->get();
  }
  
  /**
   * Get Job with status open
   * 
   * @return App\Models\Jobs
   */
  public function getJobWithStatusOpen()
  {
    //job status open with Id = 1
    return Jobs::where('job_status_id', '1')->orderBy('request_date', 'DESC')->get();
  }

  /**
   * Check job has QuestionResponses
   * @return bool
   */
  public function checkJobHasQuestionResponses($job_id)
  {
    $cv = FormQuestionResponses::where('job_id', $job_id)->first();
    if ($cv) {
      return $cv;
    } else {
      return false;
    }
  }

  /**
   * get QuestionResponses
   * @return array 
   */
  public function getQuestionResponsesByJobId($job_id)
  {
    return FormQuestionResponses::where('job_id', $job_id)->first();
  }

  /**
   * get list AnswerResponses
   * @return array
   */
  public function getAnswerResponsesByQuestionId($question_id)
  {
    return FormAnswerResponses::where('question_id', $question_id)->get();
  }

  /**
   * Create new QuestionResponses
   * @param
   * @return App\Models\QuestionResponses;
   */
  public function createQuestionResponses($attributes = [])
  {
    return FormQuestionResponses::create($attributes);
  }

  /**
   * Create new AnswerResponses
   * @param
   * @return App\Models\AnswerResponses;
   */
  public function createAnswerResponses($attributes = [])
  {
    return FormAnswerResponses::create($attributes);
  }

  /**
   * Get All jobs with Branches by status open and closed
   * @return App\Models\Profile
   */
  public function getJobWithSkillOnAddProfile()
  {
    return Jobs::with('Branches')->whereIn('job_status_id', [1, 2])->orderBy('request_date', 'DESC')->get();
  }

  /**
   * Check job has CV
   * @return bool
   */
  public function checkJobHasProfile($job_id)
  {
    $profile = Profile::where('job_id', $job_id)->first();
    if ($profile) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Get All branches
   * @return App\Models\Branches
   */
  public function getBranches()
  {
    return Branches::all();
  }

}
