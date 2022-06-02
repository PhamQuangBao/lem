<?php

namespace App\Repositories;

use App\Models\Branches;
use App\Models\Files;
use App\Models\Profile;
use App\Models\ProfileStatuses;
use Illuminate\Support\Facades\DB;

class ProfileRepository extends BaseRepository implements ProfileRepositoryInterface
{
    public function getModel()
    {
      return \App\Models\Profile::class;
    }

    /**
     * Get All Profile
     * @param
     * @return App\Models\Profile;
     */
    public function getJobs()
    {
        return Profile::all();
    }

    /**
     * Get All ProfileStatuses
     * @param
     * @return mixed
     */
    public function getProfileStatuses()
    {
        return ProfileStatuses::all();
    }

    /**
     * Get All Branches
     * @param
     * @return mixed
     */
    public function getBranches()
    {
        return Branches::all();
    }

    /**
     * Insert Profile 
     * @param
     * @return mixed
     */
    public function storeProfile($data)
    {
        DB::beginTransaction();
        try {
            $profile = Profile::create($data);
            DB::commit();
            return $profile;
        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollback();
        } finally {
            DB::disconnect();
        }
    }

    /**
     * Insert File 
     * @param
     * @return mixed
     */
    public function storeFile($data)
    {
        DB::beginTransaction();
        try {
            $file = Files::create($data);
            DB::commit();
            return $file;
        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollback();
        } finally {
            DB::disconnect();
        }
    }

    /**
     * Get Profile Paginate
     * @param
     * @return mixed
     */
    public function getProfilePaginate()
    {
        $profiles = Profile::orderBy('submit_date', 'DESC')->paginate(10);
        return $profiles;
    }

    /**
     * Get All Profile with Profile status New application by Job status Open
     * 
     * @return App\Models\Profile
     */
    public function getAllProfileStatusNewWithJobOpen()
    {
        //job status open with id = 1
        $job_status_id = 1;
        $profile_status_group_id = '1';
        $profiles = Profile::select('profile.id', 'profile.job_id', 'profile.name', 'profile.phone_number', 'profile.profile_status_id')->join('profile_statuses', 'profile_statuses.id', '=', 'profile.profile_status_id')->where('profile_statuses.profile_status_group_id', $profile_status_group_id)->orderBy('profile.submit_date', 'DESC')->join('jobs', 'jobs.id', '=', 'profile.job_id')->where('job_status_id', $job_status_id)->get();
        return $profiles;
    }

    /**
     * Get All Profile with Profile status In progress by Job status Open
     *
     * @return App\Models\Profile
     */
    public function getAllProfileStatusInProWithJobOpen()
    {
        //job status open with id = 1
        $job_status_id = 1;
        $profile_status_group_id = '2';
        $profiles = Profile::select('profile.id', 'profile.job_id', 'profile.name', 'profile.phone_number', 'profile.profile_status_id')->join('profile_statuses', 'profile_statuses.id', '=', 'profile.profile_status_id')->where('profile_statuses.profile_status_group_id', $profile_status_group_id)->orderBy('profile.submit_date', 'DESC')->join('jobs', 'jobs.id', '=', 'profile.job_id')->where('job_status_id', $job_status_id)->get();
        return $profiles;
    }

    /**
     * Get All Profile with Profile status Unqualified by Job status Open
     *
     * @return App\Models\Profile
     */
    public function getAllProfileStatusUnqualifiedWithJobOpen()
    {
        //job status open with id = 1
        $job_status_id = 1;
        $profile_status_group_id = '3';
        $profiles = Profile::select('profile.id', 'profile.job_id', 'profile.name', 'profile.phone_number', 'profile.profile_status_id')->join('profile_statuses', 'profile_statuses.id', '=', 'profile.profile_status_id')->where('profile_statuses.profile_status_group_id', $profile_status_group_id)->orderBy('profile.submit_date', 'DESC')->join('jobs', 'jobs.id', '=', 'profile.job_id')->where('job_status_id', $job_status_id)->get();
        return $profiles;
    }

    /**
     * Get All Profile with Profile status Qualified by Job status Open
     * 
     * @return App\Models\Profile
     */
    public function getAllProfileStatusQualifiedWithJobOpen()
    {
        //job status open with id = 1
        $job_status_id = 1;
        $profile_status_group_id = '4';
        $profiles = Profile::select('profile.id', 'profile.job_id', 'profile.name', 'profile.phone_number', 'profile.profile_status_id')->join('profile_statuses', 'profile_statuses.id', '=', 'profile.profile_status_id')->where('profile_statuses.profile_status_group_id', $profile_status_group_id)->orderBy('profile.submit_date', 'DESC')->join('jobs', 'jobs.id', '=', 'profile.job_id')->where('job_status_id', $job_status_id)->get();
        return $profiles;
    }

    /**
     * Get Profile with Profile status New application
     * @param string job_id in table Profile
     * 
     * @return App\Models\Profile
     */
    public function getProfileStatusNewOfJobId($id)
    {
        $profile_status_group_id = '1';
        $profiles = Profile::select('profile.id', 'profile.job_id', 'profile.name', 'profile.phone_number', 'profile.profile_status_id')->where('job_id', $id)->join('profile_statuses', 'profile_statuses.id', '=', 'profile.profile_status_id')->where('profile_statuses.profile_status_group_id', $profile_status_group_id)->orderBy('submit_date', 'DESC')->get();
        return $profiles;
    }

    /**
     * Get Profile with Profile status In progress
     * @param string job_id in table Profile
     * 
     * @return App\Models\Profile
     */
    public function getProfileStatusInProOfJobId($id)
    {
        $profile_status_group_id = '2';
        $profiles = Profile::select('profile.id', 'profile.job_id', 'profile.name', 'profile.phone_number', 'profile.profile_status_id')->where('job_id', $id)->join('profile_statuses', 'profile_statuses.id', '=', 'profile.profile_status_id')->where('profile_statuses.profile_status_group_id', $profile_status_group_id)->orderBy('submit_date', 'DESC')->get();
        return $profiles;
    }

    /**
     * Get Profile with Profile status Unqualified
     * 
     * @param string job_id in table Profile
     * 
     * @return App\Models\Profile
     */
    public function getProfileStatusUnqualifiedOfJobId($id)
    {
        $profile_status_group_id = '3';
        $profiles = Profile::select('profile.id', 'profile.job_id', 'profile.name', 'profile.phone_number', 'profile.profile_status_id')->where('job_id', $id)->join('profile_statuses', 'profile_statuses.id', '=', 'profile.profile_status_id')->where('profile_statuses.profile_status_group_id', $profile_status_group_id)->orderBy('submit_date', 'DESC')->get();
        return $profiles;
    }

    /**
     * Get Profile with Profile status Qualified
     * 
     * @param string job_id in table Profile
     * 
     * @return App\Models\Profile
     */
    public function getProfileStatusQualifiedOfJobId($id)
    {
        $profile_status_group_id = '4';
        $profiles = Profile::select('profile.id', 'profile.job_id', 'profile.name', 'profile.phone_number', 'profile.profile_status_id')->where('job_id', $id)->join('profile_statuses', 'profile_statuses.id', '=', 'profile.profile_status_id')->where('profile_statuses.profile_status_group_id', $profile_status_group_id)->orderBy('submit_date', 'DESC')->get();
        return $profiles;
    }

    /**
     * @param job_id, profile_status_id, branch_id, date_from_to, search_val
     * 
     * 
     * @return App\Models\Profile
     */
    public function getProfileListByFilter($job_id, $profile_status_id, $branch_id, $date_from_to, $search_val)
    {
        $profiles = Profile::with('profileJobs');

        if(!empty($job_id)){
            $profiles = $profiles->where('job_id', $job_id);
        }
        if(!empty($profile_status_id)){
            $profiles = $profiles->where('profile_status_id', $profile_status_id);
        }
        if(!empty($branch_id)){
            $arr_branch_id = explode(',', $branch_id);
            //You can pass the necessary variables from the parent scope into the closure with the use keyword.
            $profiles = $profiles->whereHas('profileJobs', function($q) use ($arr_branch_id) {
                $q->whereIn('branch_id', $arr_branch_id);
            });
        }
        if(!empty($date_from_to)){
            $arr_date = explode(',', $date_from_to);
            $profiles = $profiles->whereBetween('submit_date', $arr_date);
        }
        if(!empty($search_val)){
            $profiles = $profiles->where('name', 'LIKE', "%{$search_val}%");
        }
        $profiles = $profiles->orderBy('submit_date', 'DESC')->paginate(10);
        return $profiles;
    }
    
    /**
     * Check email is exits in Profile
     * @param String email
     * @return Profile
     */
    public function checkEmailIsExits($strEmail){
        $profile = Profile::where('mail', $strEmail)->first();
        if($profile){
            return $profile;
        }
        return false;
    }

    public function findFiles($id)
    {
        $files = Files::where('profile_id', '=', $id)->get();

        return $files;
    }

    // public function deleteFile($id)
    // {
    //     $result = Files::find($id);

    //     if ($result) {
    //         $result->delete();

    //         return true;
    //     }

    //     return false;
    // }
}