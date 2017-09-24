<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log
{
    public static function addEntry(string $type, int $idUser)
    {
        $ci = &get_instance();
        $ci->load->database();

        $data = ["type"        => $type,
                 "user_iduser" => $idUser];
        $ci->db->insert('log', $data);
    }
    
    public static function addLogin($idUser)
    {
        $ci = &get_instance();
        $ci->load->database();
        
        $ci->db->where('iduser', $idUser);
        $ci->db->set('login_count', 'login_count + 1', FALSE);
        $ci->db->update('user');
    }

    public static function getChangeLog(bool $recent = false): array
    {
        $ci = &get_instance();
        $ci->load->model('Changelog_model', 'changelog');

        $options = [
            'order_by' => 'date',
            'order_by_dir' => 'desc',
        ];
        if ($recent) {
            $options['limit'] = 3;
        }

        return $ci->changelog->getAll($options);
    }
}