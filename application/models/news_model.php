<?php

class News_model extends IBOT_Model {

    public function __construct() {
        
    }

    /**
     * 
     * @param type $slug
     * @return type
     */
    public function get_news($limit, $start) {
        
            $query = $this->db
                    ->order_by("date_posted", "desc")
                    ->limit(intval($limit), intval($start))
                    ->get('ci_news');
            return $query->result_array();
    }
    
    /**
     * add_news
     * 
     * @param type $data
     */
    public function add_news($data) {
        $this->db->insert('ci_news', array(
            'author' => $data['author'],
            'text' => nl2br($data['text']),
            'date_posted' => time())
        );
    }
    
    /**
     * get_article
     * @param type $id
     */
    public function get_article($id) {
        $resp = $this->db
                ->get_where('ci_news', array(
                    'id' => intval($id)
                ));
        
        $resp = $resp->row_array();
        
        if (isset($resp['id']) && is_array($resp)) {
            return $resp;
        } else {
            return false;
        }
    }
    
    public function get_newest_article() {
        $resp = $this->db
                ->limit(1)
                ->order_by("date_posted", "desc")
                ->get('ci_news');
        
        $resp = $resp->row_array();
        
        if (is_array($resp)) {
            return $resp;
        } else {
            return false;
        }
    }
    
    /**
     * count_news
     * 
     * @return type
     */
    public function count_news() {
        return $this->db->count_all('ci_news');
    }

}