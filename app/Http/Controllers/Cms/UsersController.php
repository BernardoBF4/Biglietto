<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UsersService;
use Illuminate\Http\Request;

class UsersController extends Controller
{
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
    $users_service = new UsersService($request->all(), null, null);
    $result = $users_service->create();
    return redirect()->back()->with('message', $result);
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
    $users_service = new UsersService($request->all(), $id, null);
    $result = $users_service->update();
    return redirect()->back()->with('message', $result);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  string  $users_id
   * @return \Illuminate\Http\Response
   */
  public function destroy($users_id)
  {
    $users_service = new UsersService(null, null, $users_id);
    $result = $users_service->delete();
    return redirect()->back()->with('message', $result);
  }
}
