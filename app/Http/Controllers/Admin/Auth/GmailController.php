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

    public function listProfile(Request $request)
    {
        $data = $request->all();

        //Get $dateFrom = $aRanges[0] and $dateTo[1] = $aRanges[1]
        $aRanges = explode(' - ', $data['dateRange']);
        $aRanges[1] = Carbon::parse($aRanges[1])->addDay()->format('Y-m-d');

        $jobs_status_opens = $this->jobRepository->getJobWithStatusOpen();
        $branches = $this->jobRepository->getBranches();
        foreach ($branches as $branch) {
            $branchNames[$branch->id] =  $branch->name;
        }

        if (LaravelGmail::check()) {
            $mailAlls = (LaravelGmail::message()->in(env('profile'))->after($aRanges[0])->before($aRanges[1])->all($pageToken = null));
            // dd($mailAlls);
            if (count($mailAlls) > 0) {
                foreach ($mailAlls as $key => $mailAll) {
                    $mail = LaravelGmail::message()->get($mailAll->id);
                    $mailIDs[] = $mail->getId();
                    $timeSends[] = $mail->getDate();
                    $subjects[$key] = $mail->getSubject();
                    //param: [CprofileN]-Apply for ... *branch name*
                    //get *kill name* -> branch_id -> job_id has status open, branch_id, lasted
                    //return: job_id

                    $jobID = 1;
                    $jobKey = '00-00';
                    foreach ($branchNames as $branchID => $branchName) {
                        $pos = strpos($subjects[$key], $branchName);
                        if ($pos !== false) {
                            // dd($branchID);
                            //$branchID[] = $branchID;
                            foreach ($jobs_status_opens as $jobs_status_open) {
                                // dd($jobs_status_open->branch_id);
                                // dd($branchID);
                                if ($jobs_status_open->branch_id == $branchID) {
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


                //Check Email exits in Profile
                $emailsProfile = $this->profileRepository->getEmailsProfile();
                if (isset($emailsProfile) && isset($fromMails)) {
                    // chuyen mang 2 chieu thanh 1 chieu array[][] -> array[]
                    $emailsProfile = array_column($emailsProfile, 'mail');
                    //so sanh array[], return 0 if $mailFromMails is exit $emailsProfile
                    foreach ($fromMails as $key => $fromMail) {
                        if (in_array($fromMail, $emailsProfile))
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

                return view('admin.gmail.list-profile', [
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

        //Check Email exits in Profile
        $emailsProfile = $this->profileRepository->getEmailsProfile();
        // chuyen mang 2 chieu thanh 1 chieu array[][] -> array[]
        $emailsProfile = array_column($emailsProfile, 'mail');
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
        $profileForEmailAlls =  $this->profileForEmailRepository->getAll();
        
        if (count($profileForEmailAlls) > 0) {
            foreach ($profileForEmailAlls as $profileForEmailAll) {
                if (isset($profileForEmailAll->email_id) && in_array($profileForEmailAll->email_id, $selectMailIDs)) {
                    return view('admin.gmail.home', [
                        'title' => 'Get Gmail',
                        'dateRange' => $data['dateRange'],
                        'error' => 'Error! Have email is added: '. $profileForEmailAll->form_email .' !',
                    ]);
                }
            }
        }

        
        
        // $profileForEmails[][] ;
        $i = 0;
        //  foreach ($statuses as $key => $status) {
        foreach ($mailIDs as $key => $mailID) {
            //Check mail has checked and not attachment
            if (in_array($mailID, $selectMailIDs) && ($statuses[$key] != 3)) {

                // check array[] email, return 0 if $mailFromMails is exit $emailsProfile and delete email old or remove history
                // dd($selectMailIDs);
                foreach ($fromMails as $fromMail) {
                    if (in_array($fromMail, $emailsProfile)) {
                        $profileLast =  $this->profileRepository->getProfileForEmailsLast($fromMail);
                        if(isset($profileLast->id)){
                            // $interviewLast = $this->profileRepository->getInterviewForProfileId($profileLast->id);
                            $dataProfileHistory = [
                                'profile_data' => json_encode($profileLast),
                                'mail' => $profileLast->mail,
                                // 'interview_data' => json_encode($interviewLast),
                            ];
                            $profileHistory = $this->profileHistoryRepo->create($dataProfileHistory);
                            //delete profile and interview
                            // if(isset($interviewLast)){
                            //     $interviewLast->delete();
                            // }
                            $profileLast->delete();
                        }
                        $profileLast = array();
                    }
                }
                $profileForEmails[$i]['jobIDs'] = $jobIDs[$key];
                $profileForEmails[$i]['mailIDs'] = $mailIDs[$key];
                $profileForEmails[$i]['fromMails'] = $fromMails[$key];
                $profileForEmails[$i]['timeSends'] = $timeSends[$key];
                $profileForEmails[$i]['fromNames'] = $fromNames[$key];
                $profileForEmails[$i]['subjects'] = $subjects[$key];
                $profileForEmails[$i]['phones'] = $phones[$key];
                $profileForEmails[$i]['numAttachments'] = $numAttachments[$key];

                $mail = LaravelGmail::message()->get($mailIDs[$key]);
                //Get name file
                $typeFile = pathinfo($mail->getAttachments()[0]->getFileName())['extension'];
                $new_name_file = Carbon::now()->format('Y_m_d\_His') . '_' . $this->vn_str_filter(preg_replace('/\s+/', '', ucwords(substr($fromNames[$key], 1, strlen($fromNames[$key]) - 2)))) . '.' . $typeFile;
                $profileForEmails[$i]['file'] = $new_name_file;
                $sizeFile = $mail->getAttachments()[0]->getSize();
                //If size file < 20 MB then save file
                
                if ($sizeFile < 20000000) {
                    //save Profile at public/uploads/profile
                    
                    $mail->getAttachments()[0]->saveAttachmentTo($path = 'uploads/profile', $filename = $new_name_file, $disk = 'public');
                    // dd($mail->getAttachments()[0]->saveAttachmentTo($path = 'uploads/profile', $filename = $new_name_file, $disk = 'public'));
                } else {
                    return view('admin.gmail.home', [
                        'title' => 'Get Gmail',
                        'dateRange' => $data['dateRange'],
                        'error' => 'File from email:' . $fromMails[$key] . ' is larger 20 MB!',
                    ]);
                }

                $profileForEmails[$i] = (object) $profileForEmails[$i];
                $i++;
            }
        }
        
        $profile = 0;
        //Store Profile and Profile For Email
        if (count($profileForEmails) > 0) {
            $profile =  $this->profileForEmailRepository->storeProfileForEmails($profileForEmails);
        }
        if ($profile > 0) {
            return view('admin.gmail.home', [
                'title' => 'Get Gmail',
                'dateRange' => $data['dateRange'],
                'success' => 'Have ' . $profile . ' added new Profile is successfully!',
            ]);
        } else {
            return view('admin.gmail.home', [
                'title' => 'Get Gmail',
                'dateRange' => $data['dateRange'],
                'error' => 'Error! Add new Profile has something wrong!',
            ]);
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
}
