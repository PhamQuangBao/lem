<?php

namespace App\Repositories;

use App\Models\Files;
use App\Repositories\BaseRepository;
use App\Models\Profile;
use App\Models\ProfileForEmails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProfileForEmailRepository extends BaseRepository implements ProfileForEmailRepositoryInterface
{
    //Get Model to BaseRepository 
    public function getModel()
    {
        return \App\Models\ProfileForEmails::class;
    }

    /**
     * Find Interview by profile_id
     * @param $profile_id
     * @return Interview
     */
    public function storeProfileForEmails($profileForEmails)
    {
        $count = 0;
        DB::beginTransaction();
        try {
            foreach ($profileForEmails as $data) {
                $profile = Profile::firstOrNew(array(
                    'job_id' => $data->jobIDs,
                    'submit_date' => Carbon::parse($data->timeSends)->format('Y-m-d'),
                    'name' => $data->fromNames,
                    'phone_number' => $data->phones,
                    'mail' => $data->fromMails,
                    // 'file' => $data->file,
                    // 'channel_id' => 8, //Unknown
                    'profile_status_id' => 1, //Wait for Profile screening
                    // 'university_id' => 1, //Other,
                    'note' => $data->subjects,
                ));
                $profile->save();

                if(isset($data->file)){
                    foreach($data->file as $key => $val){
                        $dataFile['profile_id'] = $profile->id;
                        $dataFile['name'] = ($data->fileName)[$key];
                        $dataFile['file'] = $val;
                        Files::create($dataFile);
                    }
                }

                $email = ProfileForEmails::create(array(
                    'profile_id' => $profile->id,
                    'email_id' => $data->mailIDs,
                    'label' => env('GOOGLE_GET_GMAIL_LABEL'),
                    'form_name' => $data->fromNames,
                    'form_email' =>  $data->fromMails,
                    'time_send' => $data->timeSends,
                    'subject' => $data->subjects,
                    'number_attachment' => $data->numAttachments,
                    'auth_email' => Auth::user()->email,
                ));
                $email->save();
                $count++;
            }
            DB::commit(); 
            // } catch (\Exception $e) {
            //     return $e->getMessage();
            // } 
        } catch (Exception $th) {
            DB::rollback();
            return 0;
        } finally {
            DB::disconnect();
            return $count;
        }
    }

        /**
     * Check Profile has profile_for_emails
     * @param $profile_id
     * @return App\Models\ProfileForEmails
     */
    public function getProfileEmailForProfileId($profile_id)
    {
        $profileForEmail = ProfileForEmails::where('profile_id', $profile_id)->first();
        if ($profileForEmail) {
            return $profileForEmail;
        } else {
            return $profileForEmail;
        }
    }

    /**
     * Check gmail id exist
     * @param $id
     * 
     * @return true|false
     */
    public function findGmailId($id)
    {
        $cvForEmail = ProfileForEmails::where('email_id', $id)->first();
        if($cvForEmail){
            return true;
        }

        return false;
    }
}
