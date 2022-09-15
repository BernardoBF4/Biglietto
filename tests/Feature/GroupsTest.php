<?php

namespace Tests\Feature;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupsTest extends TestCase
{
  // use RefreshDatabase;

  /** @test */
  public function a_logged_user_can_see_the_listing_of_groups()
  {
    $this->withoutExceptionHandling();

    $this->signIn();
    $groups = Group::factory(10)->create();

    $response = $this->get('/cms/groups');
    $response->assertViewHas('groups', function ($groups) {
      foreach ($groups as $group) {
        if (!$groups->contains($group)) {
          return false;
        }
      }

      return true;
    });
  }
}
