<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Services\EventService;

class EventController extends Controller
{
  private $service;

  public function __construct(EventService $service)
  {
    $this->service = $service;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(EventRequest $request)
  {
    $result = $this->service->create($request->all());
    return redirect()->back()->with('response', $result);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(EventRequest $request, $id)
  {
    $result = $this->service->update($id, $request->all());
    return redirect()->back()->with('response', $result);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  string  $events_id
   * @return \Illuminate\Http\Response
   */
  public function destroy($events_id)
  {
    $result = $this->service->delete($events_id);
    return redirect()->back()->with('response', $result);
  }
}
