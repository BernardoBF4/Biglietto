<?php

namespace App\Services;

use App\Interfaces\CRUD;
use App\Models\Ticket;
use Exception;

class TicketService implements CRUD
{

  public function create(array $data)
  {
    Ticket::create($data);
    return cms_response(__('ticket.success.create'));
  }

  public function update(int $id, array $data)
  {
    try {
      $ticket = $this->__findOrFail($id);
      $ticket->update($data);
      return cms_response(__('ticket.success.update'));
    } catch (\Throwable $th) {
      return cms_response($th->getMessage(), false, 400);
    }
  }

  public function delete(string $ids)
  {
  }

  private function __findOrFail(int $id)
  {
    $event = Ticket::find($id);
    if ($event instanceof Ticket) {
      return $event;
    }
    throw new Exception(__('ticket.error.not_found'));
  }
}
