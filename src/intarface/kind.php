<?php
namespace calisia_ticket_system;

interface kind
{
    public function exists($id);
    public function can_open($user_id, $element_id);
}