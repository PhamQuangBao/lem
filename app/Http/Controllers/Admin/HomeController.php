<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Repositories\JobRepositoryInterface;
use App\Repositories\ProfileRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var PostRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(
        ProfileRepositoryInterface $profileRepository, 
        JobRepositoryInterface $jobRepository,
        UserRepositoryInterface $userRepository)
    {
        $this->profileRepository = $profileRepository;
        $this->jobRepository = $jobRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $jobs_status_open = $this->jobRepository->getJobWithStatusOpen();
        $profileStatuses = $this->profileRepository->getProfileStatuses();
        $profilesNew = $this->profileRepository->getAllProfileStatusNewWithJobOpen();
        $profilesInp = $this->profileRepository->getAllProfileStatusInProWithJobOpen();
        $profilesUnqualified = $this->profileRepository->getAllProfileStatusUnqualifiedWithJobOpen();
        $profilesQualified = $this->profileRepository->getAllProfileStatusQualifiedWithJobOpen();

        // dd($this->userRepository->getRolesIDByUserID(2, [2]));
        return view('admin.home.home', [
            'title' => 'Home page',
            'jobs' => $jobs_status_open,
            'totalProfile' => count($profilesNew) + count($profilesInp) + count($profilesUnqualified) + count($profilesQualified),
            'profilesNew' => $profilesNew,
            'profilesInp' => $profilesInp,
            'profilesUnqualified' => $profilesUnqualified,
            'profilesQualified' => $profilesQualified,
            'profileStatuses' => $profileStatuses,
        ]);
    }

    public function filterHome($id)
    {
        $profileStatuses = $this->profileRepository->getProfileStatuses();
        //If select option All 
        if($id == 'All'){
            $profilesNew = $this->profileRepository->getAllProfileStatusNewWithJobOpen();
            $profilesInp = $this->profileRepository->getAllProfileStatusInProWithJobOpen();
            $profilesUnqualified = $this->profileRepository->getAllProfileStatusUnqualifiedWithJobOpen();
            $profilesQualified = $this->profileRepository->getAllProfileStatusQualifiedWithJobOpen();
            return view('admin.home.temp-home', [
                'totalProfile' => count($profilesNew) + count($profilesInp) + count($profilesUnqualified) + count($profilesQualified),
                'profilesNew' => $profilesNew,
                'profilesInp' => $profilesInp,
                'profilesUnqualified' => $profilesUnqualified,
                'profilesQualified' => $profilesQualified,
                'profileStatuses' => $profileStatuses,
            ]);
        }
        //
        else
        {
            $profilesNew = $this->profileRepository->getProfileStatusNewOfJobId($id);
            $profilesInp = $this->profileRepository->getProfileStatusInProOfJobId($id);
            $profilesUnqualified = $this->profileRepository->getProfileStatusUnqualifiedOfJobId($id);
            $profilesQualified = $this->profileRepository->getProfileStatusQualifiedOfJobId($id);
            return view('admin.home.temp-home', [
                'totalProfile' => count($profilesNew) + count($profilesInp) + count($profilesUnqualified) + count($profilesQualified),
                'profilesNew' => $profilesNew,
                'profilesInp' => $profilesInp,
                'profilesUnqualified' => $profilesUnqualified,
                'profilesQualified' => $profilesQualified,
                'profileStatuses' => $profileStatuses,
            ]);
        }
    }

    public function updateStatusProfile($request)
    {
        $arr_id = explode(',', $request);
        //profile_id = index 0 in arr_id
        $profile_id = $arr_id[0];
        //Job_id = index 2 in arr_id
        $job_id = $arr_id[2];
        $data = ['profile_status_id' => $arr_id[1]];
        $profile = $this->profileRepository->find($profile_id);
        //
        $profileStatuses = $this->profileRepository->getProfileStatuses();
        //
        if($profile){
            $profile_detail = $this->profileRepository->update($profile_id, $data);
            if ($profile_detail) {
                if($job_id == 'All'){
                    $profilesNew = $this->profileRepository->getAllProfileStatusNewWithJobOpen();
                    $profilesInp = $this->profileRepository->getAllProfileStatusInProWithJobOpen();
                    $profilesUnqualified = $this->profileRepository->getAllProfileStatusUnqualifiedWithJobOpen();
                    $profilesQualified = $this->profileRepository->getAllProfileStatusQualifiedWithJobOpen();
                    return view('admin.home.temp-home', [
                        'totalProfile' => count($profilesNew) + count($profilesInp) + count($profilesUnqualified) + count($profilesQualified),
                        'profilesNew' => $profilesNew,
                        'profilesInp' => $profilesInp,
                        'profilesUnqualified' => $profilesUnqualified,
                        'profilesQualified' => $profilesQualified,
                        'profileStatuses' => $profileStatuses,
                        'profile_detail' => $profile_detail,
                        'success' => 'Update profile status Success!'
                    ]);
                }
                else
                {
                    $profilesNew = $this->profileRepository->getProfileStatusNewOfJobId($job_id);
                    $profilesInp = $this->profileRepository->getProfileStatusInProOfJobId($job_id);
                    $profilesUnqualified = $this->profileRepository->getProfileStatusUnqualifiedOfJobId($job_id);
                    $profilesQualified = $this->profileRepository->getProfileStatusQualifiedOfJobId($job_id);
                    return view('admin.home.temp-home', [
                        'totalProfile' => count($profilesNew) + count($profilesInp) + count($profilesUnqualified) + count($profilesQualified),
                        'profilesNew' => $profilesNew,
                        'profilesInp' => $profilesInp,
                        'profilesUnqualified' => $profilesUnqualified,
                        'profilesQualified' => $profilesQualified,
                        'profileStatuses' => $profileStatuses,
                        'success' => 'Update profile status Success!'
                    ]);
                }
            } 
            //Update profile failed
            else {
                if($job_id == 'All'){
                    $profilesNew = $this->profileRepository->getAllProfileStatusNewWithJobOpen();
                    $profilesInp = $this->profileRepository->getAllProfileStatusInProWithJobOpen();
                    $profilesUnqualified = $this->profileRepository->getAllProfileStatusUnqualifiedWithJobOpen();
                    $profilesQualified = $this->profileRepository->getAllProfileStatusQualifiedWithJobOpen();
                    return view('admin.home.temp-home', [
                        'totalProfile' => count($profilesNew) + count($profilesInp) + count($profilesUnqualified) + count($profilesQualified),
                        'profilesNew' => $profilesNew,
                        'profilesInp' => $profilesInp,
                        'profilesUnqualified' => $profilesUnqualified,
                        'profilesQualified' => $profilesQualified,
                        'profileStatuses' => $profileStatuses,
                        'error' => 'Update profile has something wrong!'
                    ]);
                }
                else
                {
                    $profilesNew = $this->profileRepository->getProfileStatusNewOfJobId($job_id);
                    $profilesInp = $this->profileRepository->getProfileStatusInProOfJobId($job_id);
                    $profilesUnqualified = $this->profileRepository->getProfileStatusUnqualifiedOfJobId($job_id);
                    $profilesQualified = $this->profileRepository->getProfileStatusQualifiedOfJobId($job_id);
                    return view('admin.home.temp-home', [
                        'totalProfile' => count($profilesNew) + count($profilesInp) + count($profilesUnqualified) + count($profilesQualified),
                        'profilesNew' => $profilesNew,
                        'profilesInp' => $profilesInp,
                        'profilesUnqualified' => $profilesUnqualified,
                        'profilesQualified' => $profilesQualified,
                        'profileStatuses' => $profileStatuses,
                        'error' => 'Update profile has something wrong!'
                    ]);
                }
            }
        }
        //Not found profile with profile_id
        else{
            if($job_id == 'All'){
                $profilesNew = $this->profileRepository->getAllProfileStatusNewWithJobOpen();
                $profilesInp = $this->profileRepository->getAllProfileStatusInProWithJobOpen();
                $profilesUnqualified = $this->profileRepository->getAllProfileStatusUnqualifiedWithJobOpen();
                $profilesQualified = $this->profileRepository->getAllProfileStatusQualifiedWithJobOpen();
                return view('admin.home.temp-home', [
                    'totalProfile' => count($profilesNew) + count($profilesInp) + count($profilesUnqualified) + count($profilesQualified),
                    'profilesNew' => $profilesNew,
                    'profilesInp' => $profilesInp,
                    'profilesUnqualified' => $profilesUnqualified,
                    'profilesQualified' => $profilesQualified,
                    'profileStatuses' => $profileStatuses,
                    'error' => 'Profile not found!',
                ]);
            }
            else
            {
                $profilesNew = $this->profileRepository->getProfileStatusNewOfJobId($job_id);
                $profilesInp = $this->profileRepository->getProfileStatusInProOfJobId($job_id);
                $profilesUnqualified = $this->profileRepository->getProfileStatusUnqualifiedOfJobId($job_id);
                $profilesQualified = $this->profileRepository->getProfileStatusQualifiedOfJobId($job_id);
                return view('admin.home.temp-home', [
                    'totalProfile' => count($profilesNew) + count($profilesInp) + count($profilesUnqualified) + count($profilesQualified),
                    'profilesNew' => $profilesNew,
                    'profilesInp' => $profilesInp,
                    'profilesUnqualified' => $profilesUnqualified,
                    'profilesQualified' => $profilesQualified,
                    'profileStatuses' => $profileStatuses,
                    'error' => 'Profile not found!',
                ]);
            }
        }
        
    }
}
