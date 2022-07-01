<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Repositories\JobRepositoryInterface;
use App\Repositories\ProfileForEmailRepositoryInterface;
use App\Repositories\ProfileRepositoryInterface;
use Carbon\Carbon;
use File;
use Exception;
use Illuminate\Http\File as HttpFile;
use Spatie\GoogleCalendar\Event;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @var PostRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(
        ProfileRepositoryInterface $profileRepository,
        JobRepositoryInterface $jobRepository,
        ProfileForEmailRepositoryInterface $profileForEmailRepo
    ) {
        $this->profileRepository = $profileRepository;
        $this->jobRepository = $jobRepository;
        $this->profileForEmailRepo = $profileForEmailRepo;
        $this->event = new Event;
    }

    public function add()
    {
        $profileStatuses = $this->profileRepository->getProfileStatuses();
        $branches = $this->profileRepository->getBranches();
        $jobs = $this->jobRepository->getJobWithBranchOnAddProfile();
        $universities = $this->profileRepository->getUniversities();
        return view('admin.profile.add', [
            'title' => 'Add New Profile',
            'profileStatuses' => $profileStatuses,
            'jobs' => $jobs,
            'branches' => $branches,
            'universities' => $universities,
        ]);
    }

    /**
     * Call the route cv list again and pass the variable Session id
     * 
     */
    public function listProfileByJob($id)
    {
        return redirect('/profile/list?jobIdCallBack=' . $id);
    }

    public function list()
    {
        if (isset($_GET['jobIdCallBack'])) {
            //action to see profiles when click total profile in job list
            $profiles = $this->profileRepository->getProfileListByJob($_GET['jobIdCallBack']);
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
            
            if ($data['interviewTime']) {
                $dataInterview['time_at'] = Carbon::parse($data['interviewTime']);
                $dataInterview['time_end'] = Carbon::parse($data['interviewTime'])->addHour();
                $skills =  $this->jobRepository->find($data['job_id'])->Branches;
                
                $nameEvent = 'Interview - ' . $skills->name . ' - ' . $data['name'];
                
                //Set Calendar Google
                $this->event->name = $nameEvent;
                
                $this->event->startDateTime = $dataInterview['time_at'];
                
                $this->event->endDateTime = $dataInterview['time_end'];
                
                $calendar_key = $this->event->save()->id;
                
                $dataInterview = [
                    'calendar_key' => $calendar_key,
                    'time_at' =>  $dataInterview['time_at'],
                    'time_end' =>  $dataInterview['time_end'],
                ];
                //Insert Interview
                $createInterviews = $this->profileRepository->update($profile->id, $dataInterview);
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
                // dd($profile);
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

    public function edit($id)
    {
        $profile = $this->profileRepository->find($id);
        $universities = $this->profileRepository->getUniversities();
        // $interviews = $this->interviewRepo->findByProfileId($id);
        // $interviewers = $this->userRepository->getAll();

        if ($profile->time_at) {
            $interviewTime = Carbon::parse($profile->time_at)->format('m/d/Y h:i A');
        } else {
            $interviewTime = '';
        }
        
        $profileStatuses = $this->profileRepository->getProfileStatuses();
        $branches = $this->profileRepository->getBranches();
        // $channels = $this->profileRepository->getChannels();
        // $universities = $this->profileRepository->getUniversities();
        $jobs = $this->jobRepository->getJobWithSkillOnAddProfile();
        if ($profile) {
            return view('admin.profile.edit', [
                'title'     => 'Edit Profile',
                'profileStatuses' => $profileStatuses,
                'jobs'      => $jobs,
                'branches'    => $branches,
                // 'channels'  => $channels,
                'universities' => $universities,
                'profile'        => $profile,
                'interviewTime' => $interviewTime,
                // 'interviewers' => $interviewers
            ]);
        } else {
            return redirect()->back()->with('error', 'Profile not found!');;
        }
    }

    public function updateDetail(Request $request, $id)
    {
        $profile = $this->profileRepository->find($id);
        if ($profile) {
            $data = $request->only(['profile_status_id']);
            $profile_detail = $this->profileRepository->update($id, $data);
            if ($profile_detail) {
                return redirect()->back()->with('success', 'Update profile detail Success!');
            } else {
                return redirect()->back()->with('error', 'Update profile detail has something wrong!!');
            }
        } else {
            return redirect()->back()->with('error', 'Profile not found!');
        }
    }

    public function update(UpdateProfileRequest $request, $id)
    {
        try {
            $data = $request->all();
            $profile = $this->profileRepository->find($id);
            

            $profileStatuses = $this->profileRepository->getProfileStatuses();
            $branches = $this->profileRepository->getBranches();
            // $channels = $this->profileRepository->getChannels();
            $universities = $this->profileRepository->getUniversities();
            $jobs = $this->jobRepository->getJobWithSkillOnAddProfile();
            
            if ($data['interviewTime']) {
                $dataInterview['time_at'] = Carbon::parse($data['interviewTime']);
                $dataInterview['time_end'] = Carbon::parse($data['interviewTime'])->addHour();
                $skills =  $this->jobRepository->find($data['job_id'])->Branches;
                $nameEvent = 'Interview - ' . $skills->name . ' - ' . $data['name'];
                //Interview calendar_key is null
                if ($profile->calendar_key == null) {
                    
                    $this->event->name = $nameEvent;
                    $this->event->startDateTime = $dataInterview['time_at'];
                    $this->event->endDateTime = $dataInterview['time_end'];
                    //Add calendar
                    $calendarKey = $this->event->save()->id;

                    $dataInterview = [
                        'calendar_key' =>  $calendarKey,
                        'time_at' =>  $dataInterview['time_at'],
                        'time_end' =>  $dataInterview['time_end'],
                    ];
                    $updateProfile = $this->profileRepository->update($profile->id, $dataInterview);
                } else {
                    //update Calendar
                    if (isset($profile->calendar_key) || ($profile->time_at != $dataInterview['time_at'])) {
                        $event = Event::find($profile->calendar_key);
                        $event->update(['name' =>  $nameEvent, 'startDateTime' => $dataInterview['time_at'], 'endDateTime' => $dataInterview['time_end']]);
                    }
                    $dataInterview = [
                        //'calendar_key' => $this->event->save()->id,
                        'time_at' =>  $dataInterview['time_at'],
                        'time_end' =>  $dataInterview['time_end'],
                    ];
                    //update Interview
                    $updateProfile = $this->profileRepository->update($profile->id, $dataInterview);
                }
            } elseif (isset($profile->calendar_key)) {
                //delete interview if not interview time
                //if have calendar_key and $data['interviewTime'] == null then delete event
                $event = Event::find($profile->calendar_key);
                $event->delete();
                $dataInterview = [
                    'calendar_key' => '',
                    'time_at' =>  null,
                    'time_end' =>  null,
                ];
                $updateProfile = $this->profileRepository->update($profile->id, $dataInterview);
            }

            $files = $request->file('fileUpload');
            if ($files) {
                //delete file
                $filesDelete = $this->profileRepository->findFiles($id);
                // dd($files);
                if ($filesDelete) {
                    foreach($filesDelete as $key => $file){
                        $path_file = 'uploads/profile/';
                        $file_path = public_path($path_file . $file->file);
                        if (File::exists($file_path)){
                            File::delete($file_path);
                        }else{
                            File::delete(storage_path($path_file . $file->file));
                        }
                        $files[$key]->delete();
                    }
                }
                //update file
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
            
            //Update Profile
            $profile =  $this->profileRepository->update($id, $data);
            if ($profile) {
                return redirect('/profile/'. $profile->id .'/edit')->with(['success' => 'Update Profile is successfully!',]);
            } else {
                return redirect('/profile/'. $profile->id .'/edit')->with(['error' => 'Update Profile has something wrong!',]);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Remove Profile and Interview.
     *
     * @param  int  $profile_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $interview = $this->profileRepository->getInterviewForProfileId($id);
        $profileForEmail = $this->profileForEmailRepo->getProfileEmailForProfileId($id);
       // dd($interview['onboard_date']);
        // dd($profileForEmail);
        //delete file
        $profile = $this->profileRepository->find($id);
        
        // $file[0]->delete();
        // $files = $this->profileRepository->findFiles($id);
        
        
        // $deleteProfile = $this->profileRepository->delete($id);
        // if ($deleteProfile) {
        //     return redirect()->back()->with(['success' => 'Delete Profile is successfully!']);
        // } else {
        //     return redirect()->back()->with(['error' => 'The Profile has been deleted or has something wrong!']);
        // }
        //If profile hasn't Interview and profileForEmail then delete only profile Else delete Interview and profile
        if (!$profileForEmail) {
            
            $files = $this->profileRepository->findFiles($id);
            // dd($files);
            if ($files) {
                foreach($files as $key => $file){
                    $path_file = 'uploads/profile/';
                    $file_path = public_path($path_file . $file->file);
                    if (File::exists($file_path)){
                        File::delete($file_path);
                    }else{
                        File::delete(storage_path($path_file . $file->file));
                    }
                    $files[$key]->delete();
                }
            }

            $deleteProfile = $this->profileRepository->delete($id);
            if ($deleteProfile) {
                return redirect()->back()->with(['success' => 'Delete Profile is successfully!']);
            } else {
                return redirect()->back()->with(['error' => 'The Profile has been deleted or has something wrong!']);
            }
        } else {

            if ($profileForEmail) {
                $deleteProfileForEmail = $this->profileForEmailRepo->delete($profileForEmail->id);
            }

            $files = $this->profileRepository->findFiles($id);

            if ($files) {
                $path_file = 'uploads/profile/';
                foreach($files as $file){
                    $file_path = storage_path($path_file . $file->file);
                    if (File::exists($file_path))
                        File::delete($file_path);
                    $file->delete();
                }
                
            }
            
            $deleteProfile = $this->profileRepository->delete($id);
            // if ($file) {
            //     $path_file = 'uploads/profile/';
            //     $file_path = public_path($path_file . $profile->file);
            //     if (File::exists($file_path))
            //         File::delete($file_path);
            // }
            if ($deleteProfile) {
                return redirect()->back()->with(['success' => 'Delete Profile is successfully!']);
            } else {
                return redirect()->back()->with(['error' => 'The Profile has been deleted or has something wrong!']);
            }
        }
    }
}
