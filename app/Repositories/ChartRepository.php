<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Universities;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class ChartRepository extends BaseRepository implements ChartRepositoryInterface
{
    //Get Model to BaseRepository 
    public function getModel()
    {
        return \App\Models\Profile::class;
    }

    /**
     * Get total Job in year and year month
     * 
     * 
     * @return array $query[arr0, arr1, arr2, arr3]
     * Ex: repos = [
     *  yaer              [         2012,               2013,       2014, 2015,...]
     *  total job in year [           4,                  2,        3, 4,...]
     *  year month        [1-2012, 2-2012, 3-2012,    1-2013, 2-2013, 1-2014,...]
     *  
     * ]
     */
    public function getAllCountJobByDate()
    {
      
  
        $query = DB::table('jobs')
            ->select('jobs.request_date')
            ->where('jobs.key', '!=', '00-00')
            ->orderBy('jobs.request_date', 'ASC')
            ->get();
    
        return $query;
    }

    /**
     * get list year in submit_date
     * @return array $listYear
     */
    public function getListYearInSubmitDate()
    {
        $listYear = DB::table('profile')
            ->select( DB::raw("EXTRACT(year FROM submit_date) AS year"))
            ->groupBy('year')
            ->orderByDesc('year')
            ->get();
        return $listYear;
    }

    /**
     * get list year and month in submit_date
     * @return array $listYearMonth
     */
    public function getListYearMonthInSubmitDate()
    {
        $listYear = DB::table('profile')
            ->select( DB::raw("SUBSTRING(TO_CHAR(profile.submit_date, 'YYYY-MM-DD'), 1, 7) AS year"))
            ->groupBy('year')
            ->orderByDesc('year')
            ->get();
        return $listYear;
    }

    /**
     * get list data by year for chart TotalProfile by submit_date
     * @return array $listData
     */
    public function getTotalProfilesByYear($year)
    {
        //get total profiles and toatal interview
        $listDataProfileInterview = DB::table('profile')
            ->select( DB::raw("Count(EXTRACT(year FROM submit_date)) AS count_profiles"), DB::raw('count(interviews.onboard_date) as count_interviews'))
            ->leftJoin('interviews', 'profile.id', '=', 'interviews.profile_id')
            ->where(DB::raw("EXTRACT(year FROM submit_date)"), $year)
            ->get();
        //get total profile with statuses is offered
        $listDataOffered = DB::table('profile')
            ->where(DB::raw("EXTRACT(year FROM submit_date)"), $year)
            ->whereIn('profile_status_id', [8])
            ->get();
        return array($listDataProfileInterview, $listDataOffered);
    }

    /**
     * Get total Profile by group University
     * 
     * Danang University of Technology total 100 Profile in there 20 profile offer(group vc status onboarding), ...
     * Onboarding Id = 17, 18
     * 
     * @return array [$query_total, $total_profile_group]
     * array index 0: name University ['Danang University of Technology', 'FPT University', '...', ...]
     * array index 1: total profile university ['20.00', '10.00', '...', ...]
     * array index 2: offer University ['5.44', '3.22', '...', ...]
     * array index 3: rate University ['5', '6.33', '...', ...]
     */
    public function getAllCountProfileByUniversityID($from, $to)
    {
        $queryTotal = DB::table('profile')
            ->select('universities.name', DB::raw('COUNT(profile.id)'))
            ->leftJoin('universities', 'profile.university_id', '=', 'universities.id')
            ->whereBetween('profile.submit_date',[$from, $to])
            ->groupBy('universities.id')
            ->orderBy('universities.id')
            ->get();
        $totalProfileGroup = DB::table('profile')
            ->select('universities.name', DB::raw('COUNT(profile.id)'))
            ->leftJoin('universities', 'profile.university_id', '=', 'universities.id')
            ->whereBetween('profile.submit_date',[$from, $to])
            ->whereIn('profile_status_id', [8])
            ->groupBy('universities.id')
            ->orderBy('universities.id')
            ->get();

       return array($queryTotal, $totalProfileGroup);
    }

     /**
     * Get All Universities
     * @param
     * @return mixed
     */
    public function getAllUniversities()
    {
        return Universities::all();
    }
}
