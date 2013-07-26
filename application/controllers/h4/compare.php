<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compare extends IBOT_Controller {

    public function __construct() {
        parent::__construct();

        // helpers
        $this->load->helper('cookie');
    }
    
    public function index() {

        // try and load cookie
        if (($_tmp = get_cookie('gamertag', TRUE) != FALSE)) {
            $this->template->set('you', $_tmp);
        }

        // get gamertag and icon, send 2 template
        $_tmp = get_cookie('starred',TRUE);
        if ($_tmp != FALSE) {
            $resp = $this->stat_m->get_name_and_emblem($_tmp);
            $this->template->set('you', $resp['Gamertag']);
        }

        // validation rules
        $this->form_validation->set_rules('you_name','Your Gamertag','required|xss_clean|max_length[16]|callback_check_gt');
        $this->form_validation->set_rules('them_name', 'Their Gamertag', 'required|xss_clean|max_length[16]|callback_check_gt');

        // run validation
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
        } else {
            // set cookie of your name
            $this->input->set_cookie('gamertag',$this->input->post('you_name'),86500,'.leafapp.co','/', NULL, TRUE);

            // redirect w/ parameters to the correct thing
            $you    = $this->h4_lib->get_seo_gamertag($this->input->post('you_name'));
            $them   = $this->h4_lib->get_seo_gamertag($this->input->post('them_name'));
            redirect(base_url('h4/compare/' . $you . "/" . $them),'refresh');
        }

        $this->utils->description = "LeafApp .:. Compare Halo 4 Players";

        $this->template
            ->set("meta", $this->utils->return_meta())
            ->set_partial('modal_compare', '_partials/h4/modals/modal_compare')
            ->title("Leaf .:. Compare Halo 4 Stats")
            ->build("pages/h4/compare/compare_index");
    }

    public function comparison($you, $them) {

        // init compare library
        $this->load->library('h4_compare',array('you' => $you, 'them' => $them));

        if ($this->h4_compare->get_error()) {
            $this->template->build("pages/compare/review");
        } else {
            $data = $this->h4_compare->compare();

            $this->utils->description = "LeafApp .:. " . $data['you']['Gamertag'] . " vs " . $data['them']['Gamertag'];

            // dump to template
            $this->template
                ->set("meta", $this->utils->return_meta())
                ->set('data', $data)
                ->title("Leaf .:. Comparison: " .$data['you']['Gamertag'] . " vs " . $data['them']['Gamertag'])
                ->build("pages/compare/review");
        }
    }

    public function comparison_prefill($you = "") {
        $this->template->set("you", $you);
        $this->index();
    }


    function check_gt($gt) {
        if ($this->h4_lib->get_profile($gt, FALSE) != FALSE) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_gt','I\'m sorry, ' . $gt . ' is not a gamertag we know about.');
            return FALSE;
        }
    }
}