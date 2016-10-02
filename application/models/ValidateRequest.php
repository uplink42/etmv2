<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ValidateRequest extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Msg');
    }

    public function checkCharacterBelong($character_id, $user_id, $json = null)
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $this->db->where('iduser', $user_id);
        $query = $this->db->get('v_user_characters');

        if ($query->num_rows() != 0) {
            return true;
        } else if ($json) {
            echo Msg::INVALID_REQUEST;
        } else {
            return false;
        }

    }

    public function checkCitadelOwnership($character_id, $tax_id)
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $this->db->where('idcitadel_tax', $tax_id);
        $query = $this->db->get('citadel_tax');

        if ($query->num_rows() != 0) {
            return true;
        }

        return false;
    }

    public function checkStockListOwnership($list_id, $user_id)
    {
        $this->db->select('itemlist.iditemlist');
        $this->db->from('itemlist');
        $this->db->join('user', 'user.iduser = itemlist.user_iduser');
        $this->db->where('user.iduser', $user_id);
        $this->db->where('itemlist.iditemlist', $list_id);
        $query = $this->db->get();

        if ($query->num_rows() != 0) {
            return true;
        }
        return false;
    }

    public function checkTradeRouteOwnership($route_id, $user_id)
    {
        $this->db->where('user_iduser', $user_id);
        $this->db->where('idtraderoute', $route_id);
        $query = $this->db->get('traderoutes');
        if ($query->num_rows() != 0) {
            return true;
        }
        return false;
    }

    public function checkTransactionOwnership($transaction_id, $user_id)
    {
        $this->load->model('Login_model');
        $result = $this->Login_model->getCharacterList($user_id);
        $chars  = $result['aggr'];
        log_message('error', $chars);

        if (strlen($chars) == 0) {
            return false;
        } else {
            $this->db->select('character_eve_idcharacter, idbuy');
            $this->db->where('character_eve_idcharacter IN ' . $chars);
            $this->db->where('idbuy', $transaction_id);
            $query = $this->db->get('transaction');

            if ($query->num_rows() != 0) {
                return true;
            }
            return false;
        }
    }

}
