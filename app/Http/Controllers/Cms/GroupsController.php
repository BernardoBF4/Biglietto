<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Services\GroupsService;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $groups = (new GroupsService(null, null, null))->listAll();

    return view('cms.groups.index', compact('groups'));
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
  public function store(GroupRequest $request)
  {
    $result = (new GroupsService($request->all(), null, null))->create();
    return redirect()->back()->with('message', $result['msg']);
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
  public function update(GroupRequest $request, $id)
  {
    $result = (new GroupsService($request->all(), $id, null))->update();
    return redirect()->back()->with('message', $result['msg']);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  string  $groups_id
   * @return \Illuminate\Http\Response
   */
  public function destroy($groups_id)
  {
    $result = (new GroupsService(null, null, $groups_id))->delete();

    return redirect()->back()->with('message', $result['msg']);
  }
}
