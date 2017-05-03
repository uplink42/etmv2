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
        $this->defs          = $defs;
        $this->search_column = $search_column;
        $this->sum_column    = $sum_column;
        
        $this->filter();
        $this->paginate();
        return $this->result;
    }


    private function paginate()
    {
        $results     = $this->sort();
        $this->db->stop_cache();
        $max_query   = $this->db->get('');
        $results     = $max_query->result();
        $max_results = $max_query->num_rows();
        $sum         = $this->count($results);

        if (isset($this->defs['length']) && $this->defs['length'] < 0) {
            // pagination not requested
            $page_query   = $max_query;
            $page_results = $max_results;
        } else {
            // paginate
            $page_query   = $this->db->limit($this->defs['length'], $this->defs['start'])->get('');
            $page_results = $page_query->num_rows();
        }

        $this->generateFinalObject($page_query, $max_results, $page_results, $sum);
    }


    private function filter()
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


    private function sort()
    {
        if (isset($this->defs['order'][0])) {
            $column_idx  = $this->defs['order'][0]['column'];
            $column_name = $this->defs['columns'][$column_idx]['data'];

            $this->db->order_by($column_name, $this->defs['order'][0]['dir']);
        }
    }


    private function count($results)
    {
        $sum = 0;
        if (!empty($this->sum_column)) {
            // sum of the designated column
            for ($i = 0, $max = count($results); $i < $max; $i++) {
                $sum += $results[$i]->{$this->sum_column};
            }
        }
        
        return $sum;
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
