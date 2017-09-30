<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Chart
{
	public static function buildSparkline(array $result) : string
	{
		$data = "[";
        for ($i = 0; $i <= count($result) - 1; $i++) {
            $data .= $result[$i]->total_profit;
            if ($i < count($result) - 1) {
                $data .= ",";
            }
        }
        $data .= "]";
        return $data;
	}
}