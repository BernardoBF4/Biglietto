<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Services\GroupsService;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
  protected $group_service;

  public function __construct(GroupsService $group_service)
  {
    $this->group_service = $group_service;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $groups = $this->group_service->listAll();

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
    $result = $this->group_service->createGroupWith($request->all());

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
  public function update(GroupRequest $request, Group $group)
  {
    $result = $this->group_service->updateAGroupWith($request->all(), $group);

    return redirect()->back()->with('message', $result['msg']);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  array  $groups_id
   * @return \Illuminate\Http\Response
   */
  public function destroy($groups_id)
  {
    $result = $this->group_service->deleteGroup($groups_id);

    return redirect()->back()->with('message', $result['msg']);
  }
}
