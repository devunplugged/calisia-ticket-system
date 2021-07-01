<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class pagination{
    private $limit = 20;
    private $offset = 0;
    private $row_count = 0;
    private $no_pages = 0;
    private $treshold = 5;

    public function set_limit($limit){
        $this->limit = $limit;
    }

    public function get_limit(){
        return $this->limit;
    }

    public function get_offset(){
        return $this->offset;
    }

    public function set_page($page_no){
        $this->offset = $this->limit * ($page_no - 1);
    }

    public function get_page(){
        return ($this->offset / $this->limit) + 1;
    }

    public function set_row_count($row_count){
        $this->row_count = $row_count;
        $this->no_pages = ceil($this->row_count / $this->limit);
    }

    public function get_no_pages(){
        return $this->no_pages;
    }

    public function get_url($page=1){

        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . $this->url_params($page);
    }

    public function url_params($page){
        $params = '';
        foreach($_GET as $key => $value){
            if($key == 'show-page')
                continue;
            $params .= $key . '=' . $value . '&';
        }
        $params = rtrim($params, '&');

        if($params)
            return '?show-page=' . $page . '&' . $params;
        return '?show-page=' . $page;
    }

    public function render(){
        $pagination = '';
        $left_cut = false;
        $right_cut = false;

        for($i = 1; $i <= $this->get_no_pages(); $i++){ 
            if($i < $this->get_page() - $this->treshold){
                $left_cut = true;
                continue;
            }
            
            if($i > $this->get_page() + $this->treshold){
                $right_cut = true;
                continue;
            }
            
            $pagination .= '<a href="'.$this->get_url($i).'">'.$i.'</a> | ';
        }
        $pagination = rtrim($pagination, ' | ');

        if($left_cut)
            $pagination = '<a href="'.$this->get_url(1).'">'.__('First', 'calisia-ticket-system').'</a> | ' . $pagination;
        
        if($right_cut)
            $pagination = $pagination . ' | <a href="'.$this->get_url($this->no_pages).'">'.__('Last', 'calisia-ticket-system').'</a>';

        renderer::render('pagination/pagination', array('pagination' => $pagination));
    }
}