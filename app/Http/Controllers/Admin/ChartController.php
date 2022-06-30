<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\JobRepositoryInterface;
use App\Repositories\ChartRepositoryInterface;
use Carbon\Carbon;

class ChartController extends Controller
{
    /**
     * @var PostRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(
        JobRepositoryInterface $jobRepository,
        ChartRepositoryInterface $chartRepository
    ) {
        $this->jobRepository = $jobRepository;
        $this->chartRepository = $chartRepository;
    }

    public function university()
    {
        $minDate = '2016-01';
        $maxDate = Carbon::now()->format('Y-m');
        $from = Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->format('Y-m-d');

        $universityProfile = $this->chartRepository->getAllCountProfileByUniversityID($from, $to);
        $allUniversities = $this->chartRepository->getAllUniversities();
        foreach ($allUniversities as $allUniversity) {
            //This default array Name filter by time (case value = 0)
            $universityName2[] = $allUniversity->name;
        }

        $totalProfile = 0;
        $universityName = '';
        $universityTotal = '';
        $universityOffer = '';
        $universityRate = '';

        //$universityProfile[0] is total Profile
        foreach ($universityProfile[0] as $key => $item) {
            $totalProfile += $item->count;
        }
        
        $arrUniversityProfile = [];
        foreach($universityProfile[0] as $key => $val){
            array_push($arrUniversityProfile, $val->name);
        }

        $arrUniversityProfileOffer = [];
        foreach($universityProfile[1] as $key => $val){
            array_push($arrUniversityProfileOffer, $val->name);
        }

        foreach ($allUniversities as $key => $item) {
            $universityName = $universityName . '\'' . $item->name . '\', ';
            
            $tempCountUniversity = 0;
            if(in_array($item->name, $arrUniversityProfile) !== false){
                foreach($universityProfile[0] as $val){
                    
                    if($val->name == $item->name){
                        $universityTotal = $universityTotal . '\'' . number_format(($val->count / $totalProfile) * 100, 2, '.', '') . '\', ';
                        $tempCountUniversity = $val->count;
                        break;
                    }
                }
            }else{
                $universityTotal = $universityTotal . '\'' . 0  . '\', ';
            }
            
            //$universityProfile[1] is total Profile offer
            if(in_array($item->name, $arrUniversityProfileOffer) !== false){
                foreach($universityProfile[1] as $val){

                    if($val->name == $item->name){
                        $universityOffer = $universityOffer . '\'' . number_format(($val->count / $totalProfile) * 100, 2, '.', '') . '\', ';
                        $universityRate = $universityRate . '\'' . number_format(($val->count / $tempCountUniversity) * 100, 2, '.', '') . '\', ';
                        break;
                    }
                }
            }else{
                $universityOffer = $universityOffer . '\'' . 0 . '\', ';
                $universityRate = $universityRate . '\'' . 0 . '\', ';
            }
        }

        //  return html [$array_university, $array_total, $array_offer, $array_rate];
        $arrayUniversity = '[' . $universityName . ']';
        $arrayTotal = '[' . $universityTotal . ' 0]';
        $arrayOffer = '[' . $universityOffer . ' 0]';
        $arrayRate = '[' . $universityRate . ' 0]';

        //Request ajax when select time filter
        if (request()->ajax()) {
            $dateFrom = (string) request('dateFrom');
            $dateTo = (string) request('dateTo');

            //If chose year
            if (($dateFrom) != '' && $dateTo != '') {
                $from = Carbon::createFromFormat('Y', $dateFrom)->startOfYear()->format('Y-m-d');
                $to =  Carbon::createFromFormat('Y', $dateTo)->endOfYear()->format('Y-m-d');
            }
            $monthFrom = (string) request('monthFrom');
            $monthTo = (string) request('monthTo');

            //If chose year-month
            if ($monthFrom != '' && $monthTo != '') {
                $from = Carbon::createFromFormat('Y-m', $monthFrom)->startOfMonth()->format('Y-m-d');
                $to =  Carbon::createFromFormat('Y-m', $monthTo)->endOfMonth()->format('Y-m-d');
            }
            $universityProfile = $this->chartRepository->getAllCountProfileByUniversityID($from, $to);

            // if ((isset($universityProfile[0]) && isset($universityProfile[1])) {
            if (count($universityProfile[0]) > 0) {
                $totalProfile = 0;
                foreach ($universityProfile[0] as $key => $item) {
                    $totalProfile += $item->count;
                }

                $arrUniversityProfileGetRes = [];
                foreach($universityProfile[0] as $key => $val){
                    array_push($arrUniversityProfileGetRes, $val->name);
                }

                foreach ($allUniversities as $key => $item) {
                    if(in_array($item->name, $arrUniversityProfileGetRes) !== false){
                        foreach($universityProfile[0] as $val){
                            
                            if($val->name == $item->name){
                                $universityTotal2[] = number_format(($val->count / $totalProfile) * 100, 2, '.', '');
                                break;
                            }
                        }
                    }else{
                        $universityTotal2[] = 0;
                    }
                    //$universityProfile[1] is total Profile offer
                    if (isset($universityProfile[1][$key]->count)) {
                        $universityOffer2[] = number_format(($universityProfile[1][$key]->count / $totalProfile) * 100, 2, '.', '');
                        $universityRate2[] = number_format(($universityProfile[1][$key]->count / $item->count) * 100, 2, '.', '');
                    } else {
                        $universityOffer2[] = 0;
                        $universityRate2[] = 0;
                    }
                }
            } else {
                //set value = 0 if $universityProfile = null
                foreach ($universityName2 as $university2) {
                    $universityTotal2[] = 0;
                    $universityOffer2[] = 0;
                    $universityRate2[] = 0;
                }
            }
            return [
                'arrayUniversity' => $universityName2,
                'arrayTotal' => $universityTotal2,
                'arrayOffer' => $universityOffer2,
                'arrayRate' =>  $universityRate2
            ];
        }

        return view('admin.charts.university', [
            'title' => 'Chart total Profile by University',
            'minDate' => $minDate,
            'maxDate' => $maxDate,
            'arrayUniversity' =>  $arrayUniversity,
            'arrayTotal' => $arrayTotal,
            'arrayOffer' => $arrayOffer,
            'arrayRate' =>  $arrayRate
        ]);
    }

    public function jobs()
    {
        $jobs = $this->chartRepository->getAllCountJobByDate();
        //arr date order by asc
        if(count($jobs) != 0){
            $arrYear = [];
            $arrYearMonth = [];
            foreach($jobs as $key => $val){
                array_push($arrYear, date('Y', strtotime($val->request_date)));
                array_push($arrYearMonth, date('Y-m', strtotime($val->request_date)));
            }
                
            //bien so sanh
            $compareValYear = $arrYear[0];
        
            //set gia tri ban dau
            $arrProcessedByYear = [$arrYear[0]];
            $arrCountProcessedByYear = [1];
        
            //Loai bo phan tu trung nhau
            foreach($arrYear as $key => $val){
                //bo qua index 0
                if($key != 0){
                //Neu bang nhau
                if($val == $compareValYear){
                    //Tang bien dem le + 1
                    $arrCountProcessedByYear[count($arrCountProcessedByYear) - 1] += 1;
                }else{
                    //Neu khac gia tri thi thay doi gia tri $compareValYear
                    $compareValYear = $val;
                    array_push($arrProcessedByYear, $val);
                    array_push($arrCountProcessedByYear, 1);
                }
                }
            }
        
            $dataYear = '';
            $dataCountYear = '';
            $dataYearMonth = '';
            
            //change value in html
            foreach($arrProcessedByYear as $key => $val){
                $dataYear = $dataYear . '\'' . $val . '\', ';
                $dataCountYear = $dataCountYear . '\'' . $arrCountProcessedByYear[$key] . '\', ';
            }
            
            //change value in html
            foreach($arrYearMonth as $key => $val){
                $dataYearMonth = $dataYearMonth . '\'' . $val . '\', ';
            }
        
            $arrayJobsYear = '['. $dataYear .']';
            $arrayJobsYearTotal = '['. $dataCountYear .' 0]';
            $arrayJobsYearMonth = '['. $dataYearMonth .']';
        
            // return [$arrayJobsYear, $arrayJobsYearTotal, $arrayJobsYearMonth, $arrYearMonth[0], $arrYearMonth[count($arrYearMonth) - 1]];
            return view('admin.charts.jobs', ['title' => 'Chart total Jobs', 'arrayJobsYear' =>  $arrayJobsYear, 'arrayJobsYearTotal' => $arrayJobsYearTotal, 'arrayJobsYearMonth' => $arrayJobsYearMonth, 'minDate' => $arrYearMonth[0], 'maxDate' => $arrYearMonth[count($arrYearMonth) - 1]]);
        }
        return view('admin.charts.jobs', ['title' => 'Chart total Jobs', 'arrayJobsYear' =>  '[\'\', ]', 'arrayJobsYearTotal' => '[0]', 'arrayJobsYearMonth' => '[0]', 'minDate' => '', 'maxDate' => '']);
    }

    public function totalProfileByYear()
    {
        $listYear = $this->chartRepository->getListYearInSubmitDate();
        $listYearMonth = $this->chartRepository->getListYearMonthInSubmitDate();
        $arrayDataYear = array();
        for ($i = 0; $i < sizeof($listYear); $i++) {
            $data = $this->chartRepository->getTotalProfilesByYear($listYear[$i]->year);
            $totalData = $data[0][0]->count_profiles . ',' . count($data[2]) . ',' . count($data[1]);
            $arrayDataYear[$i] = array($listYear[$i]->year, $totalData);
        }
        $arrayDataYearMonth = array();
        for ($i = 0; $i < sizeof($listYearMonth); $i++) {
            $data = $this->chartRepository->getTotalProfilesByYearMonth($listYearMonth[$i]->year);
            $totalData = $data[0][0]->count_profiles . ',' . count($data[2]) . ',' . count($data[1]);
            $arrayDataYearMonth[$i] = array($listYearMonth[$i]->year, $totalData);
        }
        return view('admin.charts.total-profiles', [
            'title' => 'Chart total Profiles',
            'arrayX' =>  "['Total Profile', 'Total interviewed', 'Total offered',]",
            'listYear' => $arrayDataYear,
            'listYearMonth' => $arrayDataYearMonth,
        ]);
    }

    /**
     * Format
     * Ex: $query = [
     *              {'expected_salary': '7', 'current_salary': '5', 'salary_offer': '6', 'levels': 'A', 'skills': 'HR recruiter'},
     *              {'expected_salary': '7', 'current_salary': '5', 'salary_offer': '6', 'levels': 'A', 'skills': 'Unknown'},
     *              {'expected_salary': '7', 'current_salary': '5', 'salary_offer': '6', 'levels': 'B', 'skills': '.Net manager'},
     *              {'expected_salary': '7', 'current_salary': '5', 'salary_offer': '6', 'levels': 'B', 'skills': 'Unknown'},
     *              {'expected_salary': '7', 'current_salary': '5', 'salary_offer': '6', 'levels': 'C', 'skills': '.Net manager'},
     *              {'expected_salary': '7', 'current_salary': '5', 'salary_offer': '6', 'levels': 'C', 'skills': 'HR recruiter'}
     *          ]
     * @return Object
     * Ex: $salary = [
     *                  {'skills': 'HR recruiter', expected_salary: { 'A': '7', 'B': 'null', 'C': '7' }, current_salary: { 'A': '5', 'B': 'null', 'C': '5' }, salary_offer: { 'A': '6', 'B': 'null', 'C': '6'}},
     *                  {'skills': 'Unknown', expected_salary: { 'A': '7', 'B': '7', 'C': 'null' }, current_salary: { 'A': '5', 'B': '5', 'C': 'null' }, salary_offer: { 'A': '6', 'B': '6', 'C': 'null'}},
     *                  {'skills': '.Net manager', expected_salary: { 'A': 'null', 'B': '7', 'C': '7' }, current_salary: { 'A': 'null', 'B': '5', 'C': '5' }, salary_offer: { 'A': 'null', 'B': '6', 'C': '6' }},
     *              ] 
     */
    public function formatObject($data)
    {
        $salary = [];
        $arrSkill = [];
        foreach($data as $key => $val){
            //Check skills->Name already exists in $arrSkill
            if(in_array($val->skills, $arrSkill) == false){
                //Add name skill in $arrSkill
                $arrSkill[] = $val->skills;
                
                //Add sample data object in $salary
                $levelOfExpectedSalary = ['A' => 'null', 'B' => 'null', 'C' => 'null'];
                $levelOfCurrentSalary = ['A' => 'null', 'B' => 'null', 'C' => 'null'];
                $levelOfOfferSalary = ['A' => 'null', 'B' => 'null', 'C' => 'null'];
                $objTempSalary = ['skills' => 'null', 'expected_salary' => (object) $levelOfExpectedSalary, 'current_salary' => (object) $levelOfCurrentSalary, 'salary_offer' => (object) $levelOfOfferSalary];
                
                $salary[] = (object) $objTempSalary;
            }

            if($val->levels == 'A'){
                //Check skills->Name already exists in $arrSkill
                $indexArrSkill = array_search($val->skills, $arrSkill);
                if($indexArrSkill !== false){
                    $salary[$indexArrSkill] = $this->addPropertiesObject($salary[$indexArrSkill], $val, 'A');
                }else{
                    $salary[$key] = $this->addPropertiesObject($salary[$key], $val, 'A');
                }
            }
            if($val->levels == 'B'){
                //Check skills->Name already exists in $arrSkill
                $indexArrSkill = array_search($val->skills, $arrSkill);
                if($indexArrSkill !== false){
                    $salary[$indexArrSkill] = $this->addPropertiesObject($salary[$indexArrSkill], $val, 'B');
                }else{
                    $salary[$key] = $this->addPropertiesObject($salary[$key], $val, 'B');
                }
            }
            if($val->levels == 'C'){
                //Check skills->Name already exists in $arrSkill
                $indexArrSkill = array_search($val->skills, $arrSkill);
                if($indexArrSkill !== false){
                    $salary[$indexArrSkill] = $this->addPropertiesObject($salary[$indexArrSkill], $val, 'C');
                }else{
                    $salary[$key] = $this->addPropertiesObject($salary[$key], $val, 'C');
                }
            }
        }

        return $salary;
    }

    public function addPropertiesObject($data, $val, $level)
    {
        //add skill
        $data->skills = $val->skills;
        //add expected_salary
        $data->expected_salary->$level = $val->expected_salary;
        //add current_salary
        $data->current_salary->$level = $val->current_salary;
        //add salary_offer
        $data->salary_offer->$level = $val->salary_offer;

        return $data;
    }

}
