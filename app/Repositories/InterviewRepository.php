<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\ProfileStatuses;
use App\Models\Interviews;
use App\Models\Levels;
use Illuminate\Support\Facades\DB;
use Spatie\GoogleCalendar\Event;

class InterviewRepository extends BaseRepository implements InterviewRepositoryInterface
{
    //Get Model to BaseRepository 
    public function getModel()
    {
        return \App\Models\Interviews::class;
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
     * Get All Levels
     * @param
     * @return mixed
     */
    public function getLevels()
    {
        return Levels::all();
    }

    /**
     * Find Interview by profile_id
     * @param $profile_id
     * @return Interview
     */
    public function findByProfileId($profile_id)
    {
        return Interviews::where('profile_id', $profile_id)->first();
    }

    /** Delete Interview and Calendar Google
     * @param $id
     * @return bool
     */
    public function deleteAll($id)
    {
        $result = Interviews::find($id);
        if ($result) {
            if ($result->calendar_key) {
                $event = Event::find($result->calendar_key);
                if ($event)
                    $event->delete();
            }
            $result->delete();
            return true;
        }
        return false;
    }

    /**
     * Get Interviews by Job
     * @param $id
     * @return App\Models\Interviews
     */
    public function getInterviewsByJob($jobID)
    {
        $result = Interviews::join('profile', 'profile.id', '=', 'interviews.profile_id')
            ->join('jobs', 'profile.job_id', '=', 'jobs.id')
            ->where('jobs.id', $jobID)
            ->orderBy('interviews.id', 'DESC')
            ->get();
        return $result;
    }
}
