<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class StockLists_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getStockLists($user_id)
    {
        $this->db->where('user_iduser', $user_id);
        $this->db->order_by('iditemlist', 'desc');
        $query  = $this->db->get('itemlist');
        $result = $query->result();

        return $result;
    }

    public function createEmptyList($user_id, $name)
    {
        $data = array("user_iduser" => $user_id,
            "name"                      => $name);
        $query = $this->db->insert('itemlist', $data);

        if ($this->db->affected_rows() != 0) {
            log_message('error', $this->db->insert_id());
            return $this->db->insert_id();
        }
        return false;
    }

    public function getItems($id_list)
    {
        $this->db->select('i.name as name, i.volume as vol, COALESCE(p.price_evecentral,0) as price, i.eve_iditem as id');
        $this->db->from('itemcontents c');
        $this->db->join('item i', 'i.eve_iditem = c.item_eve_iditem');
        $this->db->join('item_price_data p', 'p.item_eve_iditem = i.eve_iditem', 'left');
        $this->db->join('itemlist il', 'il.iditemlist = c.itemlist_iditemlist');
        $this->db->where('il.iditemlist', $id_list);
        $this->db->order_by('c.iditemcontents', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    public function queryItems($input)
    {
        $this->db->select('name as value');
        $this->db->from('item');
        $this->db->where("name LIKE '%" . $input . "%'");
        $this->db->where("type <> 0");
        $this->db->order_by('name', 'asc');
        $this->db->limit('20');
        $query  = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function insertItem($name, $list_id)
    {
        $item  = "";
        $limit = 100;
        $this->db->where('name', $name);
        $q1 = $this->db->get('item');

        if ($q1->num_rows() == 0) {
            $notice = "error";
            $msg    = "Invalid item provided";
        } else {
            $this->db->select('count(iditemcontents) as sum');
            $this->db->where('itemlist_iditemlist', $list_id);
            $qtotal = $this->db->get('itemcontents');

            if ($qtotal->row()->sum < $limit) {
                $item_id = $q1->row()->eve_iditem;
                $data    = array("itemlist_iditemlist" => $list_id,
                    "item_eve_iditem"                      => $item_id);
                $q2 = $this->db->query("INSERT IGNORE INTO itemcontents (iditemcontents, itemlist_iditemlist, item_eve_iditem)
                VALUES ('NULL', '$list_id', '$item_id')");

                $notice = "success";
                $msg    = Msg::ITEM_ADD_SUCCESS;
                $item   = $item_id;
            } else {
                $notice = "error";
                $msg    = Msg::ITEM_MAX_REACHED . "(" . $limit . ")";
            }
        }
        return array("notice" => $notice, "message" => $msg, "item" => $item);
    }

    public function checkListBelong($list_id, $user_id)
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

    public function removeItem($item_id, $list_id)
    {
        $data = array("itemlist_iditemlist" => $list_id,
            "item_eve_iditem"                   => $item_id);

        $query = $this->db->delete('itemcontents', $data);
        if ($this->db->affected_rows() != 0) {
            $notice  = "success";
            $message = Msg::ITEM_DELETE_SUCCESS;
        } else {
            $notice  = "error";
            $message = Msg::ITEM_REMOVE_FAILURE;
        }

        return array("notice" => $notice, "message" => $message);
    }

    public function removeList($list_id)
    {
        $data = array('iditemlist' => $list_id);
        $this->db->delete('itemlist', $data);
        if ($this->db->affected_rows() != 0) {
            $notice  = "success";
            $message = Msg::LIST_REMOVE_SUCCESS;
        } else {
            $notice  = "error";
            $message = Msg::ITEM_REMOVE_FAILURE;
        }

        return array("notice" => $notice, "message" => $message);
    }

}
