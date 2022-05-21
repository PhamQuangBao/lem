<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Repositories\JobRepositoryInterface;
use App\Repositories\ProfileRepositoryInterface;
use Carbon\Carbon;
use File;
use Exception;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @var PostRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(
        ProfileRepositoryInterface $profileRepository,
        JobRepositoryInterface $jobRepository,
    ) {
        $this->profileRepository = $profileRepository;
        $this->jobRepository = $jobRepository;
    }

    public function add()
    {
        $profileStatuses = $this->profileRepository->getProfileStatuses();
        $branches = $this->profileRepository->getBranches();
        $jobs = $this->jobRepository->getJobWithBranchOnAddProfile();
        return view('admin.profile.add', [
            'title' => 'Add New Profile',
            'profileStatuses' => $profileStatuses,
            'jobs' => $jobs,
            'branches' => $branches,
        ]);
    }

    public function list()
    {
        if (isset($_GET['jobIdCallBack'])) {
            //action to see profiles when click total profile in job list
            $profiles = $this->profileRepository->getProfileListByJob($_GET['jobIdCallBack']);
        } else {
            //Get all profiles list
            $profiles = $this->profileRepository->getProfilePaginate();
        }
        $jobs = $this->jobRepository->getJobs();
        $branches = $this->profileRepository->getBranches();
        $profile_statuses = $this->profileRepository->getProfileStatuses();
        if (request()->ajax()) {

            $branch_id = request('branch_id');
            $job_id = request('job_id');
            $profile_status_id = request('profile_status_id');
            $date_from_to = request('date_from_to');
            $search_val = request('search');
            $profiles = $this->profileRepository->getProfileListByFilter($job_id, $profile_status_id, $branch_id, $date_from_to, $search_val);

            $paginate = (string) $profiles->links('admin.profile.paginate')->render();

            $result = view("admin.profile.search-ajax", compact('profiles'))->render();
            return ['result' => $result, 'pagination' => $paginate];
        }
        // dd($profiles);
        return view('admin.profile.list', ['title' => 'Profile list', 'profiles' => $profiles, 'jobs' => $jobs, 'profile_statuses' => $profile_statuses, 'branches' => $branches]);
    }

    public function store(StoreProfileRequest $request)
    {
        try {
            $data = $request->all();
            $files = $request->file('fileUpload');

            $profile =  $this->profileRepository->storeProfile($data);

            if ($files) {
                foreach($files as $key => $file){
                    $dataFile['profile_id'] = $profile->id;
                    //Example :'  Nguyễn văn têN   '
                    //ucwords($data['name']) : Đầu mỗi từ viết Hoa -> '  Nguyễn Văn TêN   '
                    //reg_replace('/\s+/', '', $string) : Loại bỏ toàn bộ khảng trắng, tab -> 'Nguyễn Văn TêN'
                    //vn_str_filter($string) : Chuyển có dấu thành không dấu -> 'NguyenVanTeN'
                    //$file->getClientOriginalExtension() : lấy kiểu file (xlsx, doc, docx)
                    //Carbon::createFromFormat('Y_m_d\_His', $time)) : 2022_03_25_092530
                    $new_name_file = Carbon::now()->format('Y_m_d\_His') . '_' . $this->vn_str_filter(preg_replace('/\s+/', '', ucwords($data['name']))) . '.' . $file->getClientOriginalExtension();
                    $path_file = 'uploads/profile/';
                    $file->move($path_file, $new_name_file);
                    $dataFile['name'] = $file->getClientOriginalName();
                    $dataFile['file'] = $new_name_file;
                    $this->profileRepository->storeFile($dataFile);
                }
            }
            
            if ($profile) {
                return redirect('/profile/add')->with(['success' => 'Add new Profile is successfully!',]);
            } else {
                return redirect('/profile/add')->with(['error' => 'Add new Profile has something wrong!',]);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    /** Filter String vn to English
     * @param: string
     * @return: string
     * Example: 'Trần vĂn Tên' -> 'Tran vAn Ten';
     */
    public function vn_str_filter($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    public function detail($id)
    {
        if ($id < 2147483647 && $id > 0) {
            $profile = $this->profileRepository->find(intval($id));
            if ($profile) {
                // dd($interview);
                $profileStatuses = $this->profileRepository->getProfileStatuses();
                $branches = $this->profileRepository->getBranches();
                $job = $this->jobRepository->findJob($profile->job_id);

                    return view('admin.profile.detail', [
                        'title'     => 'profile DETAIL',
                        'id'        => intval($id),
                        'profile'        => $profile,
                        'job'       => $job,
                        'profileStatuses' => $profileStatuses,
                        'branches'    => $branches,
                    ]);
            } else {
                return redirect()->back()->with('error', 'profile not found!');;
            }
        } else {
            return redirect()->back()->with('error', 'id out of range profile!');
        }
    }

    /**
     * Remove CV and Interview.
     *
     * @param  int  $cv_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interview = $this->cvRepository->getInterviewForCvId($id);
        $cvForEmail = $this->cvForEmailRepo->getCvEmailForCvId($id);
       // dd($interview['onboard_date']);
        // dd($cvForEmail);
        //delete file
        $cv = $this->cvRepository->find($id);
        $file = $cv->file;
        //If Cv hasn't Interview and cvForEmail then delete only CV Else delete Interview and CV
        if (!$interview && !$cvForEmail) {
            $deleteCV = $this->cvRepository->delete($id);
            if ($file) {
                $path_file = 'uploads/cv/';
                $file_path = public_path($path_file . $cv->file);
                if (File::exists($file_path))
                    File::delete($file_path);
            }
            if ($deleteCV) {
                return redirect()->back()->with(['success' => 'Delete CV is successfully!']);
            } else {
                return redirect()->back()->with(['error' => 'The CV has been deleted or has something wrong!']);
            }
        } else {

            if ($cvForEmail) {
                $deleteCvForEmail = $this->cvForEmailRepo->delete($cvForEmail->id);
            }

            //Return error exits interviewed when delete interview time
            if (isset($interview['onboard_date'])) {
                return redirect()->back()->with(['error' => 'The CV has been interviewed!']);
            }

            if(isset($interview['id'])){
                $deleteInterview = $this->interviewRepo->deleteAll($interview['id']);
            }
            
            $deleteCV = $this->cvRepository->delete($id);
            if ($file) {
                $path_file = 'uploads/cv/';
                $file_path = public_path($path_file . $cv->file);
                if (File::exists($file_path))
                    File::delete($file_path);
            }
            if ($deleteCV) {
                return redirect()->back()->with(['success' => 'Delete CV is successfully!']);
            } else {
                return redirect()->back()->with(['error' => 'The CV has been deleted or has something wrong!']);
            }
        }
    }
}
