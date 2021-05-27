<?php
namespace calisia_ticket_system\elements;

use \calisia_ticket_system as cts;

class controls{
    public static function ticket_table_controls(){

         return cts\renderer::render(
            'tables/ticket-table-controls',
            array(
                'type_select' => cts\inputs::select(
                                        array(
                                            'id' => 'calisia_ticket_system_control_ticket_type',
                                            'name' => 'calisia_ticket_system_control_ticket_type',
                                            'class' => 'select',
                                            'options' => array(
                                                __('All', 'calisia-ticket-system') => 'all',
                                                __('Order', 'calisia-ticket-system') => 'order',
                                                __('Other', 'calisia-ticket-system') => 'other'
                                            ),
                                            'value' => isset($_GET['kind']) ? $_GET['kind'] : 'all'
                                        )
                                    ),
                'status_select' => cts\inputs::select(
                                        array(
                                            'id' => 'calisia_ticket_system_control_ticket_status',
                                            'name' => 'calisia_ticket_system_control_ticket_status',
                                            'class' => 'select',
                                            'options' => array(
                                                __('All', 'calisia-ticket-system') => 'all',
                                                __('Opened', 'calisia-ticket-system') => 'opened',
                                                __('Onhold', 'calisia-ticket-system') => 'onhold',
                                                __('Awaiting Reply', 'calisia-ticket-system') => 'awaitingreply',
                                                __('Completed', 'calisia-ticket-system') => 'completed'
                                            ),
                                            'value' => isset($_GET['status']) ? $_GET['status'] : 'all'
                                        )
                                    )
            ),
            false
        );
    }
}