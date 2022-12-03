<?php

namespace App\Services;

use App\Models\Ticket;
use Exception;

class TicketService
{

  public function create(array $data)
  {
    Ticket::create($data);
    return cms_response(trans('cms.ticket.success_create'));
  }

  public function update(?Ticket $ticket, array $data)
  {
    $ticket->update($data);
    return cms_response(trans('cms.ticket.success_update'));
  }

  public function delete(string $ids)
  {
  }
}
