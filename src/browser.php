<?php
namespace calisia_ticket_system;

class browser{
    
    private $kind;
    private $order_by;
    private $page;
    private $offset;
    private $items_per_page;
    private $number_of_all_results;

    public $pagination_limit = 2;

    function __construct($settings = array()){
        if(empty($settings))
            return;

        isset($settings['kind'])            ? $this->set_kind($settings['kind'])                     : $this->kind = 'order';
        isset($settings['order_by'])        ? $this->set_order_by($settings['order_by'])             : $this->order_by = 'added';
        isset($settings['page'])            ? $this->set_page($settings['page'])                     : $this->page = 0;
        isset($settings['items_per_page'])  ? $this->set_items_per_page($settings['items_per_page']) : $this->items_per_page = 10;
    }

    public function set_kind($kind){
        switch($kind){
            case 'order': $this->kind = 'order'; break;
            default: $this->kind = 'other';
        }
    }

    public function get_kind(){
        return $this->kind;
    }

    public function set_order_by($order_by){
        switch($order_by){
            case 'title': $this->order_by = 'title'; break;
            case 'date': $this->order_by = 'added'; break;
            case 'kind': $this->order_by = 'kind'; break;
            default: $this->order_by = 'added';
        }
    }

    public function get_order_by(){
        return $this->order_by;
    }

    public function set_page($page){
        $this->page = (int)$page - 1;
        $this->offset = $this->items_per_page * $this->page;
    }

    public function get_page(){
        return $this->page + 1;
    }

    public function get_offset(){
        return $this->offset;
    }

    public function set_items_per_page($items){
        $this->items_per_page = (int)$items;
        $this->offset = $this->items_per_page * $this->page;
    }

    public function get_items_per_page(){
        return $this->items_per_page;
    }

    public function get_number_of_all_results(){
        return $this->number_of_all_results;
    }

    public function set_pagination_limit($limit){
        $this->pagination_limit = (int)$limit;
    }

    public function get_results(){
        if(
            !isset(
                $this->kind,
                $this->order_by,
                $this->page,
                $this->items_per_page
            )
        ){
            throw new Exception("required variables are not set");
        }

        $results = data::browse_query($this);
        $this->number_of_all_results = $results['number_of_all_results'];
        return $results['results'];
    }

    public function generate_pagination_array(){

        $pagination = array();
        $number_of_pages = ceil($this->number_of_all_results / $this->items_per_page);
        $pagination['left_cut'] = false;
        $pagination['right_cut'] = false;


        for($i = 0; $i < $number_of_pages; $i++){
            $element = array(
                'url' => menu_page_url( 'calisia-tickets', false ) . '&kind=' . $this->kind . '&current_page=' . ($i + 1) . '&on_page=' . $this->items_per_page,
                'page' => $i + 1
            );

            if($this->page == $i)
                $element['current'] = true;
            
            if($i == 0)
                $pagination['first'] = $element;
            
            if($i == $number_of_pages - 1)
                $pagination['last'] = $element;

            if( $i > $this->page + $this->pagination_limit ){
                $pagination['right_cut'] = true;
                continue;
            }

            if( $i < $this->page - $this->pagination_limit ){
                $pagination['left_cut'] = true;
                continue;
            }

            $pagination['elements'][] = $element;
        }

        if($pagination['right_cut'] && $this->page + $this->pagination_limit != $number_of_pages - 2)
            $pagination['elements'][] = array('url'=>'','page'=>'...');

        if($pagination['left_cut'] && $this->page - $this->pagination_limit != 1)
            array_unshift($pagination['elements'], array('url'=>'','page'=>'...'));

        return $pagination;
      //  $pagination[] = array('url' => 'aaaa', 'name' =>);
    }
}