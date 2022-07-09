<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Cv;
use App\Models\CvStatuses;
use App\Models\Skills;
use App\Models\Channels;
use Illuminate\Broadcasting\Channel;

interface InterviewRepositoryInterface extends BaseRepositoryInterface
{
   
    /**
     * Get All CvStatuses
     * @param
     * @return mixed
     */
    public function getProfileStatuses();

    public function findByProfileId($profile_id);

    public function getLevels();

    public function getInterviewsByJob($jobID);

}