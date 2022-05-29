<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\JobRepositoryInterface;
use App\Repositories\ProfileForEmailRepositoryInterface;
use App\Repositories\ProfileRepositoryInterface;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class GmailController extends Controller
{
    /**
     * @var UserRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(UserRepositoryInterface $userRepository, JobRepositoryInterface $jobRepository, ProfileRepositoryInterface $profileRepository, ProfileForEmailRepositoryInterface $profileForEmailRepository)
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
        $this->jobRepository = $jobRepository;
        $this->profileForEmailRepository = $profileForEmailRepository;
        // $this->profileHistoryRepo = $profileHistoryRepo;
    }

    public function home()
    {
        $jobs_status_open = $this->jobRepository->getJobWithStatusOpen();
        if (!LaravelGmail::check()) {
            return view('admin.gmail.home', [
                'title' => 'Home Gmail',
                // 'jobs' => $jobs_status_open,
            ]);
        } else {
            return view('admin.gmail.home', [
                'title' => 'Login Gmail',
            ]);
        }
    }

    public function loginGmail()
    {
        return LaravelGmail::redirect()->with([
            'title' => 'Home Gmail',
        ]);
    }

    public function listCV(Request $request)
    {
        $data = $request->all();

        //Get $dateFrom = $aRanges[0] and $dateTo[1] = $aRanges[1]
        $aRanges = explode(' - ', $data['dateRange']);

        $jobs_status_opens = $this->jobRepository->getJobWithStatusOpen();
        $skills = $this->jobRepository->getSkills();
        foreach ($skills as $skill) {
            $skillNames[$skill->id] =  $skill->name;
        }

        if (LaravelGmail::check()) {
            $mailAlls = (LaravelGmail::message()->in(env('GOOGLE_GET_GMAIL_LABEL'))->after($aRanges[0])->all($pageToken = null));
            if (count($mailAlls) > 0) {
                foreach ($mailAlls as $key => $mailAll) {
                    $mail = LaravelGmail::message()->get($mailAll->id);
                    $mailIDs[] = $mail->getId();
                    $timeSends[] = $mail->getDate();
                    $subjects[$key] = $mail->getSubject();
                    //param: [CCVN]-Apply for ... *skill name*
                    //get *kill name* -> skill_id -> job_id has status open, skill_id, lasted
                    //return: job_id

                    $jobID = 1;
                    $jobKey = '00-00';
                    foreach ($skillNames as $skillID => $skillName) {
                        $pos = strpos($subjects[$key], $skillName);
                        if ($pos !== false) {
                            // dd($skillID);
                            //$skillID[] = $skillID;
                            foreach ($jobs_status_opens as $jobs_status_open) {
                                // dd($jobs_status_open->skill_id);
                                // dd($skillID);
                                if ($jobs_status_open->skill_id == $skillID) {
                                    $jobID = $jobs_status_open->id;
                                    $jobKey = $jobs_status_open->key;
                                    break;
                                }
                            }
                        }
                    }

                    $jobIDs[] = $jobID;
                    $jobKeys[] = $jobKey;

                    $fromNames[] = $mail->getFromName();
                    $fromMails[] = $mail->getFromEmail();
                    $numAttachments[] = count($mail->getAttachments());
                    // param 1: 0912345678 || +84 0912345678 || (+84) 0912345678 || +084-0912345678
                    // return 1: 0912345678 
                    // param: +84 912345678
                    // return: 84 912345678
                    // param : 084 912345678 || +084 912345678
                    // return: 084 912345678
                    // param 2: 912345678 ||  (+084) 912345678 || +084-912345678
                    // return 2: 912345678
                    // param 3: 0912 345 678 || +84 0912 345 678 || (+84) 0912 345 678
                    // return 3: 0912 345 678
                    // param 4: 0912-345-678 || +84 0912-345-678 || (+84)-0912-345-678
                    // return 4: 0912-345-678
                    // else return: 000000000
                    $textBody = $mail->getPlainTextBody();
                    preg_match(
                        '/[0-9]{10}|[0-9]{2}[\s][0-9]{9}|[0-9]{3}[\s][0-9]{9}|[0-9]{9}|[0-9]{4}[\s][0-9]{3}[\s][0-9]{3}|[0-9]{4}[\-][0-9]{3}[\-][0-9]{3}/',
                        $textBody,
                        $phoneMails
                    );
                    if (isset($phoneMails[0])) {
                        $phones[] = $phoneMails[0];
                    } else {
                        $phones[] = '0000000000';
                    }

                    $statuses[] = 1;
                }


                //Check Email exits in CV
                $emailsCV = $this->cvRepository->getEmailsCV();
                if (isset($emailsCV) && isset($fromMails)) {
                    // chuyen mang 2 chieu thanh 1 chieu array[][] -> array[]
                    $emailsCV = array_column($emailsCV, 'mail');
                    //so sanh array[], return 0 if $mailFromMails is exit $emailsCV
                    foreach ($fromMails as $key => $fromMail) {
                        if (in_array($fromMail, $emailsCV))
                            $statuses[$key] = 0;
                    }
                }

                //@return @array 'key' => 'value' 
                // ['email@email.com' => '1']
                // ['email2@email.com' => '2']
                $countExits = array_count_values($fromMails);

                // this cycle echoes all associative array
                // key where value > 1
                // return @array emailError
                while ($countEmails = current($countExits)) {
                    if ($countEmails > 1) {
                        $emailErrors[] = key($countExits);
                    }
                    next($countExits);
                }

                //Check Duplicate Email return $status = 2
                if (isset($emailErrors)) {
                    foreach ($fromMails as $key => $fromMail) {
                        foreach ($emailErrors as $emailError) {
                            if (($fromMail == $emailError) && ($statuses[$key] == 1)) {
                                $statuses[$key] = 2;
                            }
                        }
                    }
                }

                //If $numAttachments = 0 return 3
                foreach ($numAttachments as $key => $numAttachment) {
                    if (($numAttachment == 0) && ($statuses[$key] == 1)) {
                        $statuses[$key] = 3;
                    }
                }

                return view('admin.gmail.list-cv', [
                    'title' => 'Get Gmail',
                    'mailIDs' => $mailIDs,
                    'timeSends' => $timeSends,
                    'subjects' => $subjects,
                    'phones' => $phones,
                    'fromNames' => $fromNames,
                    'fromMails' => $fromMails,
                    'numAttachments' => $numAttachments,
                    'statuses' => $statuses,
                    'jobIDs' => $jobIDs,
                    'jobKeys' => $jobKeys,
                    'dateRange' => $data['dateRange'],
                ]);
            } else {
                return view('admin.gmail.home', [
                    'title' => 'Get Gmail',
                    'dateRange' => $data['dateRange'],
                    'error' => 'Error! No have email',
                ]);
            }
        } else {
            return view('admin.gmail.home', [
                'title' => 'Get Gmail',
                'dateRange' => $data['dateRange'],
                'error' => 'Please login gmail!',
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     * @param 
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $selectMailIDs = $data['selectSaves'];

        $jobIDs = unserialize($request->jobIDs);
        $mailIDs = unserialize($request->mailIDs);
        $timeSends = unserialize($request->timeSends);
        $subjects = unserialize($request->subjects);
        $phones = unserialize($request->phones);
        $fromNames = unserialize($request->fromNames);
        $fromMails = unserialize($request->fromMails);
        $numAttachments = unserialize($request->numAttachments);
        $statuses = unserialize($request->statuses);

        //Check Email exits in CV
        $emailsCV = $this->cvRepository->getEmailsCV();
        // chuyen mang 2 chieu thanh 1 chieu array[][] -> array[]
        $emailsCV = array_column($emailsCV, 'mail');
        //get Mail for MailID
        foreach ($mailIDs as $key => $mailID) {
            if (in_array($mailID, $selectMailIDs)) {
                $selectMails[] =  $fromMails[$key];
            }
        }

        //Check count value return Error!
        $countMails = array_count_values($selectMails);
        foreach ($countMails as $key => $countMail) {
            if ($countMail > 1) {
                // $emailError[] = $key;
                return view('admin.gmail.home', [
                    'title' => 'Get Gmail',
                    'dateRange' => $data['dateRange'],
                    'error' => 'Error! Email : ' . $key . ' is duplicate!',
                ]);
            }
        }

        //check mail for gmail is exits
        $cvForEmailAlls =  $this->cvForEmailRepository->getAll();
        if (count($cvForEmailAlls) > 0) {
            foreach ($cvForEmailAlls as $cvForEmailAll) {
                if (isset($cvForEmailAll->email_id) && in_array($cvForEmailAll->email_id, $selectMailIDs)) {
                    return view('admin.gmail.home', [
                        'title' => 'Get Gmail',
                        'dateRange' => $data['dateRange'],
                        'error' => 'Error! Have email is added: '. $cvForEmailAll->form_email .' !',
                    ]);
                }
            }
        }

        

        // $cvForEmails[][] ;
        $i = 0;
        //  foreach ($statuses as $key => $status) {
        foreach ($mailIDs as $key => $mailID) {
            //Check mail has checked and not attachment
            if (in_array($mailID, $selectMailIDs) && ($statuses[$key] != 3)) {

                // check array[] email, return 0 if $mailFromMails is exit $emailsCV and delete email old or remove history
                // dd($selectMailIDs);
                foreach ($fromMails as $fromMail) {
                    if (in_array($fromMail, $emailsCV)) {
                        $cvLast =  $this->cvRepository->getCvForEmailsLast($fromMail);
                        if(isset($cvLast->id)){
                            $interviewLast = $this->cvRepository->getInterviewForCvId($cvLast->id);
                            $dataCVHistory = [
                                'cv_data' => json_encode($cvLast),
                                'mail' => $cvLast->mail,
                                'interview_data' => json_encode($interviewLast),
                            ];
                            $cvHistory = $this->cvHistoryRepo->create($dataCVHistory);
                            //delete cv and interview
                            if(isset($interviewLast)){
                                $interviewLast->delete();
                            }
                            $cvLast->delete();
                        }
                        $cvLast = array();
                    }
                }
                $cvForEmails[$i]['jobIDs'] = $jobIDs[$key];
                $cvForEmails[$i]['mailIDs'] = $mailIDs[$key];
                $cvForEmails[$i]['fromMails'] = $fromMails[$key];
                $cvForEmails[$i]['timeSends'] = $timeSends[$key];
                $cvForEmails[$i]['fromNames'] = $fromNames[$key];
                $cvForEmails[$i]['subjects'] = $subjects[$key];
                $cvForEmails[$i]['phones'] = $phones[$key];
                $cvForEmails[$i]['numAttachments'] = $numAttachments[$key];

                $mail = LaravelGmail::message()->get($mailIDs[$key]);
                //Get name file
                $typeFile = pathinfo($mail->getAttachments()[0]->getFileName())['extension'];
                $new_name_file = Carbon::now()->format('Y_m_d\_His') . '_' . CvController::vn_str_filter(preg_replace('/\s+/', '', ucwords($fromNames[$key]))) . '.' . $typeFile;
                $cvForEmails[$i]['file'] = $new_name_file;

                $sizeFile = $mail->getAttachments()[0]->getSize();
                //If size file < 20 MB then save file
                if ($sizeFile < 20000000) {
                    //save CV at public/uploads/cv
                    $mail->getAttachments()[0]->saveAttachmentTo($path = 'uploads/cv', $filename = $new_name_file, $disk = 'public');
                } else {
                    return view('admin.gmail.home', [
                        'title' => 'Get Gmail',
                        'dateRange' => $data['dateRange'],
                        'error' => 'File from email:' . $fromMails[$key] . ' is larger 20 MB!',
                    ]);
                }

                $cvForEmails[$i] = (object) $cvForEmails[$i];
                $i++;
            }
        }

        $cv = 0;
        //Store CV and CV For Email
        if (count($cvForEmails) > 0) {
            $cv =  $this->cvForEmailRepository->storeCvForEmails($cvForEmails);
        }
        if ($cv > 0) {
            return view('admin.gmail.home', [
                'title' => 'Get Gmail',
                'dateRange' => $data['dateRange'],
                'success' => 'Have ' . $cv . ' added new CV is successfully!',
            ]);
        } else {
            return view('admin.gmail.home', [
                'title' => 'Get Gmail',
                'dateRange' => $data['dateRange'],
                'error' => 'Error! Add new CV has something wrong!',
            ]);
        }
    }
}
