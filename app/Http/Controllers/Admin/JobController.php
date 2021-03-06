<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Repositories\InterviewRepositoryInterface;
use App\Repositories\JobRepositoryInterface;
use App\Repositories\ProfileHistoryRepositoryInterface;
use App\Repositories\ProfileRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use File;

class JobController extends Controller
{
    /**
     * @var PostRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(
        JobRepositoryInterface $jobRepository,
        ProfileRepositoryInterface $profileRepository,
        ProfileHistoryRepositoryInterface $profileHistoryRepo,
        InterviewRepositoryInterface $interviewRepo,
    ) {
        $this->jobRepository = $jobRepository;
        $this->profileRepository = $profileRepository;
        $this->event = new Event;
        $this->profileHistoryRepo = $profileHistoryRepo;
        $this->interviewRepo = $interviewRepo;
    }

    public function list()
    {
        $jobs = $this->jobRepository->getJobWithStatus();
        $jobStatuses = $this->jobRepository->getJobStatuses();
        $branches = $this->jobRepository->getAllBranch();
        return view('admin.jobs.list', ['title' => 'Job list', 'jobs' => $jobs, 'jobStatuses' => $jobStatuses, 'branches' => $branches]);
    }

    public function add()
    {
        $lastJobNow = $this->jobRepository->findLastJob((int)date("Y"));
        $lastJobBack = $this->jobRepository->findLastJob((int)date("Y") - 1);
        $lastJobNext = $this->jobRepository->findLastJob((int)date("Y") + 1);
        $listBranch = $this->jobRepository->getAllBranch();
        $listStatus = $this->jobRepository->getJobStatuses();
        return view('admin.jobs.add', [
            'title' => 'add new job',
            'listStatus' => $listStatus,
            'listBranch' => $listBranch,
            'lastJobNow' => intval($lastJobNow),
            'lastJobBack' => intval($lastJobBack),
            'lastJobNext' => intval($lastJobNext),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJobRequest $request)
    {
        $data = $request->all();
        $job =  $this->jobRepository->createJob($data);
        $listStatus = $this->jobRepository->getJobStatuses();
        $listBranch = $this->jobRepository->getAllBranch();
        if ($job) {
            return redirect()->back()->with([
                'title' => 'add new job',
                'listStatus' => $listStatus,
                'listBranch' => $listBranch,
                'success' => 'Add new job is successfully!',
                'job_key' => $job->key,
            ]);
        } else {
            return redirect()->back()->with([
                'title' => 'add new job',
                'listStatus' => $listStatus,
                'listBranch' => $listBranch,
                'error' => 'Add new job has something wrong!',
            ]);
        }
    }

    public function show($id)
    {
        $job_statuses = $this->jobRepository->getJobStatuses();
        $job = $this->jobRepository->findJob($id);
        $interviewTime = '';
        if($job->time_at){
            $interviewTime = Carbon::parse($job->time_at)->format('m/d/Y h:i A');
        }
        if ($job) {
            $checkHasQuestionResponses = $this->jobRepository->checkJobHasQuestionResponses($id);
            $arrProfileInterview = array();
            $arrProfileOffer = array();
            $arrProfileUnqualified = array();
            foreach($job->Profile as $key => $item){
                $idStatusGroups = $item->profileStatus->profile_status_group_id;

                // Profile Interview
                if($idStatusGroups == 2){
                    $arrProfileInterview[] = $item;
                }
                // Profile Unqualified
                if($idStatusGroups == 3){
                    $arrProfileUnqualified[] = $item;
                }
                // Profile Offer
                if($idStatusGroups == 4){
                    $arrProfileOffer[] = $item;
                }
            }

            if($checkHasQuestionResponses) {
                //show responses if job has responses
                $QuestionResponses = $this->jobRepository->getQuestionResponsesByJobId($id);
                $AnswerResponses = $this->jobRepository->getAnswerResponsesByQuestionId($QuestionResponses->id);
                $arrAnswer = array();
                foreach ($AnswerResponses as $answer) {
                    array_push($arrAnswer, json_decode($answer->answer, true));
                }
                for ($i=0; $i < count($arrAnswer); $i++) { 
                    if($this->profileRepository->checkEmailIsExits($arrAnswer[$i]['answer_3'])){
                        $arrAnswer[$i]['check'] = true;
                    }
                }
                return view('admin.jobs.detail', [
                    'title' => 'Job Detail',
                    'job' => $job,
                    'job_statuses' => $job_statuses,
                    'arrQuestion' => json_decode($QuestionResponses->question, true),
                    'arrAnswer' =>  $arrAnswer,
                    'arrProfileInterview' => $arrProfileInterview,
                    'arrProfileOffer' => $arrProfileOffer,
                    'arrProfileUnqualified' => $arrProfileUnqualified,
                    'interviewTime' => $interviewTime,
                ]);
            }
            return view('admin.jobs.detail', [
                'title' => 'Job Detail', 
                'job' => $job, 
                'job_statuses' => $job_statuses,
                'arrProfileInterview' => $arrProfileInterview,
                'arrProfileOffer' => $arrProfileOffer,
                'arrProfileUnqualified' => $arrProfileUnqualified,
                'interviewTime' => $interviewTime,
            ]);
        } else {
            return redirect()->back()->with('error', 'Job not found!');
        }
    }

    public function updateDetail(Request $request, $id)
    {
        $data = $request->only('job_status_id');
        if ($data) {
            $job_detail = $this->jobRepository->updateJob($id, $data);
            return redirect()->back()->with('success', 'Update Job Success!');
        } else {
            return redirect()->back()->with('error', 'Update Job has something wrong!!');
        }
    }

    /**
     * Show edit job pages.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = $this->jobRepository->findJob($id);
        $listStatus = $this->jobRepository->getJobStatuses();
        $listBranch = $this->jobRepository->getAllBranch();
        if ($job) {
            return view('admin.jobs.edit', [
                'title' => 'edit job',
                'listStatus' => $listStatus,
                'listBranch' => $listBranch,
                'job' => $job,
            ]);
        } else {
            return redirect()->back()->with('error', 'Job not found!');;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJobRequest  $UpdateJobRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJobRequest $request, $id)
    {
        $data = $request->all();
        $listStatus = $this->jobRepository->getJobStatuses();
        $listBranch = $this->jobRepository->getAllBranch();
        $jobUpdate =  $this->jobRepository->updateJob($id, $data);
        if ($jobUpdate) {
            return redirect()->back()->with([
                'title' => 'edit job',
                'listStatus' => $listStatus,
                'listBranch' => $listBranch,
                'job' => $jobUpdate,
                'success' => 'Update job is successfully!',
            ]);
        } else {
            return redirect()->back()->with([
                'title' => 'edit job',
                'listStatus' => $listStatus,
                'listBranch' => $listBranch,
                'job' => $jobUpdate,
                'error' => 'Update job has something wrong!',
            ]);
        }
    }

    public function addInterviewDateByJob(Request $request)
    {
        if(isset($request->interviewTime)){
            $interviewDate = $request->interviewTime;
            $jobId = $request->jobId;
            $jobs = $this->jobRepository->find($jobId);

            if(count($jobs->Profile) != 0){
                
                $dataInterview['time_at'] = Carbon::parse($interviewDate);
                $dataInterview['time_end'] = Carbon::parse($interviewDate)->addHour();
                $nameEvent = 'Group interview -' . $jobs->Branches->name;
                if($jobs->calendar_key){
                    //update calendar
                    if($jobs->time_at != $dataInterview['time_at']){
                        $event = Event::find($jobs->calendar_key);
                        $event->update(['name' =>  $nameEvent, 'startDateTime' => $dataInterview['time_at'], 'endDateTime' => $dataInterview['time_end']]);
                        $this->jobRepository->update($jobId, ['time_at' => $dataInterview['time_at'], 'time_end' => $dataInterview['time_end']]);
                    }

                    //update profile list not calendar
                    foreach($jobs->Profile as $key => $profile){
                        $dataInterview = [
                            'time_at' =>  $dataInterview['time_at'],
                            'time_end' =>  $dataInterview['time_end'],
                        ];
                        //Create or update  Interview
                        
                        if($profile->calendar_key == ""){
                            $profile->update($dataInterview);
                        }
                    }
                }
                else{
                    //Set Calendar Google
                    $this->event->name = $nameEvent;
                    $this->event->startDateTime = $dataInterview['time_at'];
                    $this->event->endDateTime = $dataInterview['time_end'];
                    $calendar_key = $this->event->save()->id;
                    
                    
                    foreach($jobs->Profile as $key => $profile){
                        $dataInterview = [
                            'time_at' =>  $dataInterview['time_at'],
                            'time_end' =>  $dataInterview['time_end'],
                        ];
                        
                        if($profile->calendar_key == ""){
                            $profile->update($dataInterview);
                        }
                    }
                    $this->jobRepository->update($jobId, ['calendar_key' => $calendar_key, 'time_at' => $dataInterview['time_at'], 'time_end' => $dataInterview['time_end']]);
                }
                
                return redirect()->back()->with('success', ' Save Interview Date is successfully!');
            }else{
                return redirect()->back()->with('error', " Can't find  Profiles to save Interview Date!");
            }
        }
        return redirect()->back()->with('error', ' Save Interview Date has something wrong!');
    }

    public function importResponses(Request $request)
    {
        $file = $request->file('fileUpload');
        //read fileUpload
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);
        //add data from file to array
        $dataExcel = $spreadsheet->getSheet(0)->toArray();
        //read question row
        $arrQuestion = array();
        for ($i = 0; $i < count($dataExcel[0]); $i++) {
            if ($dataExcel[0][$i]) {
                array_push($arrQuestion, $dataExcel[0][$i]);
            }
        }
        //read all answer row
        $arrAnswer = array();
        for ($y=1; $y < count($dataExcel); $y++) {
            for ($i=0; $i < count($dataExcel[$y]); $i++) {
                if ($dataExcel[$y][$i]) {
                    $arrAnswer[$y-1]['check'] = false;
                    $arrAnswer[$y-1]['answer_'.$i] = $dataExcel[$y][$i];
                }
            }
        }
        
        //check job exists
        $job_statuses = $this->jobRepository->getJobStatuses();
        $job = $this->jobRepository->find($request->jobId);
        //time calendar
        $interviewTime = '';
        if($job->time_at){
            $interviewTime = Carbon::parse($job->time_at)->format('m/d/Y h:i A');
        }
        if ($job) {
            $arrProfileInterview = array();
            $arrProfileOffer = array();
            $arrProfileUnqualified = array();
            foreach($job->Profile as $key => $item){
                $idStatusGroups = $item->profileStatus->profile_status_group_id;

                // Profile Interview
                if($idStatusGroups == 2){
                    $arrProfileInterview[] = $item;
                }
                // Profile Unqualified
                if($idStatusGroups == 3){
                    $arrProfileUnqualified[] = $item;
                }
                // Profile Offer
                if($idStatusGroups == 4){
                    $arrProfileOffer[] = $item;
                }
            }
            return view('admin.jobs.detail', [
                'title' => 'Job Detail',
                'job' => $job,
                'job_statuses' => $job_statuses,
                'arrAnswer' => $arrAnswer,
                'arrQuestion' => $arrQuestion,
                'arrProfileInterview' => $arrProfileInterview,
                'arrProfileOffer' => $arrProfileOffer,
                'arrProfileUnqualified' => $arrProfileUnqualified,
                'interviewTime' => $interviewTime,
                'addResponses' => true
            ]);
        } else {
            return redirect()->back()->with('error', 'Job not found!');
        }
    }

    public function storeResponses(Request $request)
    {
        try {
            $data = $request->all();
            // dd($data);
            //check job exists
            $job = $this->jobRepository->find(intval($data['job_id']));
            if ($job) {
                //delete old Responses
                $checkHasQuestionResponses = $this->jobRepository->checkJobHasQuestionResponses($job->id);
                if ($checkHasQuestionResponses) {
                    $QuestionResponses = $this->jobRepository->getQuestionResponsesByJobId($job->id);
                    $AnswerResponses = $this->jobRepository->getAnswerResponsesByQuestionId($QuestionResponses->id);
                    //delete old answer
                    foreach ($AnswerResponses as $answer) {
                        $answer = $answer->delete();
                    }
                    //delete old Question
                    $QuestionResponses = $QuestionResponses->delete();
                }
                //store new Responses
                //store Question
                $qs = $this->jobRepository->createQuestionResponses([
                    'job_id' => $job->id,
                    'question'      => $data['question'],
                ]);
                //store Answers
                if (isset($request->answer)) {
                    $listAnswer = $request->answer;
                    for ($i = 0; $i < count($listAnswer); $i++) {
                        $this->jobRepository->createAnswerResponses([
                            'question_id' => $qs->id,
                            'answer'      => $listAnswer[$i],
                        ]);
                    }
                }
                return redirect('/jobs/' . $data['job_id'] . '/detail')->with('success', 'Save respose is successfully!');
            } else {
                return redirect('/jobs/list')->with('error', 'Job not found!');
            }
        } catch (\Throwable $th) {
            return redirect('/jobs/' . $data['job_id'] . '/detail')->with('error', 'Save respose has something wrong!');
        }
    }

    public function checkListProfile(Request $request){
        //check has any list checkbox is checked
        if (isset($request->selectSave)) {
            $job_id = $request->job_id;
            $listRequestSelectSave = $request->selectSave;
            $listResults = array();
            //colecttion data
            for ($i=count($listRequestSelectSave)-1; $i > -1 ; $i--) {
                $profile = json_decode($listRequestSelectSave[$i]);
                $data = [
                    'mail' => $profile->answer_3,
                    'submit_date' => Carbon::now()->format('Y-m-d'),
                    'phone_number' => $profile->answer_4,
                    'name' => $profile->answer_2,
                    'job_id' => $job_id,
                    'university_id' => $this->checkUniversity($profile->answer_5)['id'],
                    'university_name'=>$this->checkUniversity($profile->answer_5)['name'],
                    'profile_status_id' => 3,
                    'link' => end($profile),
                    'year_of_experience' => 0,
                ];
                //check is duplicate email and not exits
                foreach($listResults as $index => $item){
                    if($item['mail'] === $data['mail']){
                        if(!$this->profileRepository->checkEmailIsExits($data['mail'])){
                            $listResults[$index]['status'] = 'duplicate';
                            $data['status'] = 'duplicate';
                            break;
                        }
                    }
                }
                //check is exits email
                $checkExitsEmail = $this->profileRepository->checkEmailIsExits($data['mail']);
                if(!$checkExitsEmail){
                    if(empty($data['status'])){
                        $data['status'] = 'saved';
                    }
                }else{
                    if($checkExitsEmail->profileStatus->profile_status_group_id == 3){
                        $data['status'] = 'unqualified';
                    }
                    else {
                        $data['status'] = 'exits';
                    }
                }
                array_push($listResults, $data);
                $data = array();
            }
            return view('admin.jobs.list-profile', [
                'title' => 'List profile preview',
                'listProfile' => $listResults,
            ]);
        }
        return redirect()->back()->with('error', 'Please choose some one!');
       
    }

    public function saveListProfile(Request $request){
        //check has any list checkbox is checked
        if(isset($request->selectSave)){
            $listRequestSelectSave = $request->selectSave;
            $count = 0;
            //save list Profiles
            
            for ($i=0; $i < count($listRequestSelectSave) ; $i++) { 
                $profile = json_decode($listRequestSelectSave[$i]);
                if(!$this->profileRepository->checkEmailIsExits($profile->mail) && empty($data["status"])){
                    $this->profileRepository->create((array) $profile);
                    $count++;
                }
                //save email exit with status profile unqualified
                if($profile->status == "unqualified"){
                    $profileUnqualified = $this->profileRepository->checkEmailIsExits($profile->mail);
                    $dataProfileHistory = [
                        'profile_data' => json_encode($profileUnqualified),
                        'mail' => $profileUnqualified->mail,
                    ];
                    $profileHistory = $this->profileHistoryRepo->create($dataProfileHistory);
                    
                    //delete file
                    $files = $this->profileRepository->findFiles($profileUnqualified->id);
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
                    

                    //find intervew profile and delete
                    $interview = $this->interviewRepo->findByProfileId($profileUnqualified->id);
                    if($interview){
                        $interview->delete();
                    }

                    //create new profile 
                    $this->profileRepository->create((array) $profile);
                    $count++;

                    //delete profile
                    $profileUnqualified->delete();

                }
            }
            return redirect('/jobs/'. $profile->job_id .'/detail')->with('success', 'Save '.$count.' is successfully!');
        }
        return redirect('/jobs/'. $request->job_id .'/detail')->with('error', 'Please choose some one!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $checkHasProfile = $this->jobRepository->checkJobHasProfile($id);
        if (!$checkHasProfile) {
            $checkHasResponses = $this->jobRepository->checkJobHasQuestionResponses($id);
            if ($checkHasResponses) {
                $listAnswerResponses = $this->jobRepository->getAnswerResponsesByQuestionId($checkHasResponses->id);
                foreach ($listAnswerResponses as $answerResponses) {
                    $answerResponses->delete();
                }
                $checkHasResponses->delete();
            }
            $job = $this->jobRepository->delete($id);
            if ($job) {
                return redirect()->back()->with(['success' => 'Delete job is successfully!']);
            } else {
                return redirect()->back()->with(['error' => 'The Job has been deleted or has something wrong!']);
            }
        }
        return redirect()->back()->with(['error' => "Can't delete job, because This job has a profile!"]);
    }

    /**
     * check string channel
     * @return array['id'=> $idChannel, 'name'=> $channelName]
     */
    public function checkChannels($strChannel){
        $listChannels = $this->CvRepository->getChannels();
        $idChannel = 1;
        $channelName = "";
        foreach($listChannels as $channel){
            if($strChannel === $channel->name){
                $idChannel = $channel->id;
                $channelName = $channel->name;
                break;
            }
        }
        return  ['id'=> $idChannel, 'name'=> $channelName];
    }

    // /**
    //  * check string University
    //  * @return array['id'=> $idUniversity, 'name'=> $universityName]
    //  */
    // public function checkUniversity($strUniversity){
    //     $listUniversitys = $this->CvRepository->getUniversities();
    //     $idUniversity = 1;
    //     $universityName = "";
    //     foreach($listUniversitys as $university){
    //         if($strUniversity === $university->name){
    //             $idUniversity = $university->id;
    //             $universityName = $university->name;
    //             break;
    //         }
    //     }
    //     return   ['id'=> $idUniversity, 'name'=> $universityName];
    // }

    /**
     * check string University
     * @return array $idUniversity
     */
    public function checkUniversity($strUniversity){
        $listUniversitys = $this->profileRepository->getUniversities();
        $idUniversity = 1;
        $universityName = "";
        foreach($listUniversitys as $university){
            if(strpos($university->name, '(') !== FALSE){
                $temp = substr($university->name, strpos($university->name, '(') + 1, strlen($university->name) - (strpos($university->name, '(') + 1) - 1);
                
                if(strpos($strUniversity, $temp) !== FALSE){
                    $idUniversity = $university->id;
                    $universityName = $university->name;
                    break;
                }
            }
        }
        return ['id'=> $idUniversity, 'name'=> $universityName];
    }
}
