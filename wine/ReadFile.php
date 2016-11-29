<?php

namespace wine;

require 'Log.php';

use wine\Log;

class ReadFile {

    /**
     * 
     * @param type $url 
     * @param type $path
     */
    public function readFileFromUrl($url, $path) {

        $newfname = $path;
        $file = fopen($url, 'rb');
        if ($file) {
            $newf = fopen($newfname, 'wb');
            if ($newf) {
                while (!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
        Log::logInfo('log', 'downloaded url -' . $url . ' to file name-' . $newfname);
    }

    public function readFileFromSystem($filename) {
        //#total wine vine yard provided for test
        $wine_array = [];
        //#total no of people tested the wine
        $person_array = [];
        //#list of wine teted by people wise
        $person_wine_test = [];
        $file = fopen($filename, 'rb');
        if ($file) {

            while (!feof($file)) {
                $line = fgets($file);
                echo $line.PHP_EOL;
                $data_array = preg_split('/\s+/', $line);
                //print_r($data_array);
                //die('exit');
//uncomment below line to break loop
                if(count($person_array)>5000){
                    break;
                }
                $person_id = isset($data_array[0]) ? trim($data_array[0]) : null;
                $wine_id = isset($data_array[1]) ? trim($data_array[1]) : null;
                if (!in_array($wine_id, $wine_array)) {
                    $wine_array[] = $wine_id;
                }
                if (!in_array($person_id, $person_array)) {
                    $person_array[] = $person_id;
                }
                $person_wine_test[$person_id][] = $wine_id;
            }
            $sold_wine = $this->soldwine($person_wine_test);
            $result_data = ['wine_list' => $wine_array, 'person_list' => $person_array, 'person_wine_list' => $person_wine_test];
            //Log::logInfo('wine', 'total-'.count($wine_array).PHP_EOL.'data-'.print_r($wine_array, true));
            //Log::logInfo('person', 'total-'.count($person_array).PHP_EOL.'data-'.print_r($person_array, true));
            //Log::logInfo('person_wine_list', 'total-'.count($person_wine_test).PHP_EOL.'data-'.print_r($person_wine_test, true));
        }
        if ($file) {
            fclose($file);
        }

        //Log::logInfo('log', 'file read done -' . $filename);
    }
    /*
     * deprecated not in use
     */
    public function segregateWineData($data) {
        $wine_array = [];
        $person_array = [];
        $person_wine_test = [];
        $data_array = preg_split('/\s+/', $data);
        $wine_id = isset($data_array[0]) ? trim($data_array[0]) : null;
        $person_id = isset($data_array[1]) ? trim($data_array[1]) : null;
        if (!in_array($wine_id, $wine_array)) {
            $wine_array[] = $wine_id;
        }
        if (!in_array($person_id, $person_array)) {
            $person_array[] = $person_id;
        }
        $person_wine_test[$person_id][] = $wine_id;
        
        $result_data = ['wine_list' => $wine_array, 'person_list' => $person_array, 'person_wine_list' => $person_wine_test];
        return $result_data;
    }
    /**
     * generate final result array from picked data
     * @param type $data
     */
    public function soldwine($data){
        $sold_wine = [];
        $final_array = [];
        foreach($data as $key=>$value){
            //echo 'person - '.$key.PHP_EOL;
            foreach($value as $wine){
                if(isset($final_array[$key]) && count($final_array[$key])>2){
                    break;
                }
                if(!in_array($wine, $sold_wine)){
                    $sold_wine[] = $wine;
                    $final_array[$key][] = $wine;
                }
            }
        }
        $this->createFinalFile($sold_wine, $final_array);
        //Log::logInfo('final_list', 'total wine sold-'.count($sold_wine).PHP_EOL.'person wine-'.print_r($final_array, true));
        
    }
    /**
     * generate result file after getting final data
     * @param type $wine_list
     * @param type $final_list
     */
    public function createFinalFile($wine_list, $final_list){
        $filename = 'result.txt';
        $file = fopen($filename, 'wb');
        fwrite($file, "total sold wine -:".count($wine_list).PHP_EOL);
        foreach($final_list as $person_id=>$wine_list){
            foreach($wine_list as $wine_id){
                $line = $person_id."\t".$wine_id.PHP_EOL;
                fwrite($file, $line);
            }
        }
        fclose($file);
    }

}
