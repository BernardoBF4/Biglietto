<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\CmsUserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
  private $service;

  public function __construct(CmsUserService $service)
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
  public function store(UserRequest $request)
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
  public function update(UserRequest $request, $id)
  {
    $result = $this->service->update($id, $request->all());
    return redirect()->back()->with('response', $result);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  string  $users_id
   * @return \Illuminate\Http\Response
   */
  public function destroy($users_id)
  {
    $result = $this->service->delete($users_id);
    return redirect()->back()->with('response', $result);
  }
}
