<?php

namespace App\Services;

use App\Models\Ticket;
use App\Interfaces\CRUD;
use Exception;

class TicketService implements CRUD
{

  public function create(array $data)
  {
    Ticket::create($data);
    return cms_response(trans('cms.ticket.success_create'));
  }

  public function update(int $id, array $data)
  {
  }

  public function delete(string $ids)
  {
  }
}
