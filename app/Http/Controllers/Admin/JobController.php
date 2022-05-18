<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Repositories\JobRepositoryInterface;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * @var PostRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(
        JobRepositoryInterface $jobRepository,
    ) {
        $this->jobRepository = $jobRepository;
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
        if ($job) {
            return view('admin.jobs.detail', ['title' => 'Job Detail', 'job' => $job, 'job_statuses' => $job_statuses]);
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

    public function checkListCV(Request $request){
        //check has any list checkbox is checked
        if (isset($request->selectSave)) {
            $intern_job_id = $request->intern_job_id;
            $listRequestSelectSave = $request->selectSave;
            $listResults = array();
            //colecttion data
            for ($i=count($listRequestSelectSave)-1; $i > -1 ; $i--) {
                $cv = json_decode($listRequestSelectSave[$i]);
                $data = [
                    'mail' => $cv->answer_3,
                    'submit_date' => Carbon::now()->format('Y-m-d'),
                    'phone_number' => $cv->answer_4,
                    'name' => $cv->answer_2,
                    'job_id' => $intern_job_id,
                    'university_id' => $this->checkUniversity($cv->answer_5)['id'],
                    'university_name'=>$this->checkUniversity($cv->answer_5)['name'],
                    'channel_id' => $this->checkChannels($cv->answer_6)['id'],
                    'channel_name' => $this->checkChannels($cv->answer_6)['name'],
                    'cv_status_id' => 3,
                    'link' => end($cv),
                    'year_of_experience' => 0,
                ];
                //check is duplicate email and not exits
                foreach($listResults as $index => $item){
                    if($item['mail'] === $data['mail']){
                        if(!$this->internCvRepository->checkEmailIsExits($data['mail'])){
                            $listResults[$index]['status'] = 'duplicate';
                            $data['status'] = 'duplicate';
                            break;
                        }
                    }
                }
                //check is exits email
                if(!$this->internCvRepository->checkEmailIsExits($data['mail'])){
                    if(empty($data['status'])){
                        $data['status'] = 'saved';
                    }
                }else{
                    $data['status'] = 'exits';
                }
                array_push($listResults, $data);
                $data = array();
            }
            return view('admin.intern-jobs.list-cv', [
                'title' => 'List intern cv preview',
                'listInternCV' => $listResults,
            ]);
        }
        return redirect()->back()->with('error', 'Please choose some one!');
       
    }
    public function saveListCV(Request $request){
        //check has any list checkbox is checked
        if(isset($request->selectSave)){
            $listRequestSelectSave = $request->selectSave;
            $count = 0;
            //save list Cvs
            for ($i=0; $i < count($listRequestSelectSave) ; $i++) { 
                $cv = json_decode($listRequestSelectSave[$i]);
                if(!$this->internCvRepository->checkEmailIsExits($cv->mail) && empty($data["status"])){
                    $this->internCvRepository->create((array) $cv);
                    $count++;
                }
            }
            return redirect('/intern-jobs/'. $cv->job_id .'/detail')->with('success', 'Save '.$count.' is successfully!');
        }
        return redirect('/intern-jobs/'. $request->intern_job_id .'/detail')->with('error', 'Please choose some one!');
    }

    /**
     * check string channel
     * @return array['id'=> $idChannel, 'name'=> $channelName]
     */
    public function checkChannels($strChannel){
        $listChannels = $this->internCvRepository->getChannels();
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

    /**
     * check string University
     * @return array['id'=> $idUniversity, 'name'=> $universityName]
     */
    public function checkUniversity($strUniversity){
        $listUniversitys = $this->internCvRepository->getUniversities();
        $idUniversity = 1;
        $universityName = "";
        foreach($listUniversitys as $university){
            if($strUniversity === $university->name){
                $idUniversity = $university->id;
                $universityName = $university->name;
                break;
            }
        }
        return   ['id'=> $idUniversity, 'name'=> $universityName];
    }
}
