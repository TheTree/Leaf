<?php

class News_model extends IBOT_Model {

    public function __construct() {
        
    }

    /**
     * @param $limit
     * @param $start
     * @internal param \type $slug
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
            'author'        => $data['author'],
            'text'          => nl2br($data['text']),
            'title'         => $data['title'],
            'slug'          => " ",
            'date_posted'   => time())
        );

        return $this->db->insert_id();
    }

    /**
     * update_slug
     *
     *
     * @param $id
     * @param $title
     */
    public function update_slug($id, $title) {
        $this->db
            ->where('id', intval($id))
            ->update('ci_news', array(
                'slug' => $title . "-" . $id
            ));
    }
    
    /**
     * get_article
     * @param type $id
     * @return array|bool
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
            return FALSE;
        }
    }

    /**
     * get_article_via_slug
     *
     * Gets an article via slug
     * @param $slug
     * @return bool
     */
    public function get_article_via_slug($slug) {
        $resp = $this->db
                ->get_where('ci_news', array(
                'slug'  => $slug
            ));

        $resp = $resp->row_array();

        if (isset($resp['slug']) && is_array($resp)) {
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * get_newest_article
     *
     * Grabs newest article from `ci_news`
     *
     * @return array|bool
     */
    public function get_newest_article() {
        $resp = $this->db
                ->limit(1)
                ->order_by("date_posted", "desc")
                ->get('ci_news');
        
        $resp = $resp->row_array();
        
        if (is_array($resp)) {
            return $resp;
        } else {
            return FALSE;
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