<?php

namespace App;

use App\Utility;

class MyCsv
{
    public static function csv_to_assocoative_array($file, $indirect = False)
    {
        if ($indirect) {
            $file = storage_path() . "/app/" . $file;
            //echo $file;
        }
        $file = fopen($file, "r");
        $fields = fgetcsv($file);
        $col_count = count($fields);
        for ($i = 0; $i < $col_count; $i++) {
            $fields[$i] = Utility::filter($fields[$i]);
            if (strlen($fields[$i]) < 1) {
                throw new \Exception ("Field Name Is Empty");
            }
        }
        $arr = [];
        $j = 0;
        while (!feof($file)) {
            $row = fgetcsv($file);
            if ($row == FALSE) continue;
            $col_count = count($row);
            $temp_arr = [];
            $j++;
            for ($i = 0; $i < $col_count; $i++) {
                $row_value = Utility::filter($row[$i]);
                $temp_arr[$fields[$i]] = $row_value;
                if (strlen($row_value) < 1) {
                    throw new \Exception("Cell Is Empty on Row  $j ");
                }
            }
            array_push($arr, $temp_arr);
        }
        fclose($file);
        return $arr;
    }

}


?>