<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;

class Ahp extends Model
{
    public function get_row_total($matrix)
    {
        $arr = array();
        foreach($matrix as $key => $val){
            foreach($val as $i => $v){
                $arr[$i] += $v;
            }
        }
        return $arr;
    }

    public function normalize($matrix, $rowTotal)
    {
        $arr = array();
        foreach($matrix as $key => $val){
            foreach($val as $i => $v){
                $arr[$key][$i] = $v / $rowTotal[$i];
            }
        }
        return $arr;
    }

    public function get_criteria_weights($normal)
    {
        $arr = array();
        foreach($normal as $key => $val){
            $arr[$key] = array_sum($val) / count($val);
        }
        return $arr;
    }

    public function get_consistency_vector($matrix, $criteriaWeights)
    {
        $arr = array();
        foreach($matrix as $key => $val){
            foreach($val as $i => $v){
                $arr[$key] += $v * $criteriaWeights[$i];
            }
        }
        foreach($arr as $key => $val){
            $arr[$key] = $val / $criteriaWeights[$key];
        }
        return $arr;
    }

    public function get_consistency_radio($consistencyVector)
    {
        $arr = array();
        $sum = array_sum($consistencyVector);
        $count = count($consistencyVector);

        $arr['ci'] = (($sum / $count) - $count) / ($count - 1);
        $nRI = array(
            1=>0,
            2=>0,
            3=>0.58,
            4=>0.9,
            5=>1.12,
            6=>1.24,
            7=>1.32,
            8=>1.41,
            9=>1.46,
            10=>1.49,
            11=>1.51,
            12=>1.48,
            13=>1.56,
            14=>1.57,
            15=>1.59
        );
        $arr['ri'] = $nRI[$count];
        $arr['cr'] = $arr['ci'] / $arr['ri'];
        return $arr;
    }
}
