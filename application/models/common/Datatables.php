<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datatables extends CI_Model
{
    private $query;
    private $defs;
    private $result;

    public function __construct()
    {
        parent::__construct();
        
    }

    public function generate(array $defs, string $search_column, string $sum_column)
    {
        $this->defs  = $defs;
        $this->search_column = $search_column;
        $this->sum_column    = $sum_column;
        $this->createFiltering();
        $this->createPagination();
        return $this->result;
    }


    private function createPagination()
    {
        $this->db->stop_cache();
        $max_query   = $this->db->get('');

        $sum = 0;
        if (!empty($this->sum_column)) {
            // sum of the designated row
            $results = $max_query->result_array();
            for ($i = 0, $max = count($results); $i < $max; $i++) {
                $sum += $results[$i][$this->sum_column];
            }
        }
        
        $max_results = $max_query->num_rows();
        if (isset($this->defs['length']) &&  $this->defs['length'] < 0) {
            $page_query   = $max_query;
            $page_results = $max_results;
        } else {
            $page_query   = $this->db->limit($this->defs['length'], $this->defs['start'])->get('');
            $page_results = $page_query->num_rows();
        }

        $this->generateFinalObject($page_query, $max_results, $page_results, $sum);
    }


    private function createFiltering()
    {
        if (empty($this->defs['search']['value'])) {
            return false;
        }

        /*$columns = [];
        foreach($this->defs['columns'] as $column) {
            if ($column['searchable']) {
                $this->db->or_where($column['data'], $this->defs['search']['value']);
            }
        }*/

        $this->db->like($this->search_column, $this->defs['search']['value']);
    }


    private function generateFinalObject($query, $max, $page, $sum)
    {
        $result = [
            'data' => $query->result(),
            'max'  => $max,
            'page' => $page,
            'draw' => $this->defs['draw'],
            'sum'  => number_format($sum,2)
        ];

        $this->db->flush_cache();
        $this->result = $result;
    }
}
