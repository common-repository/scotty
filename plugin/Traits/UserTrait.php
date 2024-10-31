<?php

namespace NSScottyPlugin\Traits;

use NSScottyPlugin\Traits\MantineUITrait;

trait UserTrait
{
  use MantineUITrait;

  /**
   * Get the count of users.
   *
   * @return int
   */
  public function getUsersCount()
  {
    $users = get_users();

    return count($users);
  }

  /**
   * Get the count of users per role.
   *
   * @return array
   */
  public function getUsersCountPerRole()
  {
    $users = get_users();

    $role_count = [];

    foreach ($users as $user) {
      $user_info = get_userdata($user->ID);
      $user_roles = $user_info->roles;

      foreach ($user_roles as $role) {
        if (isset($role_count[$role])) {
          $role_count[$role]++;
        } else {
          $role_count[$role] = 1;
        }
      }
    }

    ksort($role_count);
    $results = [];
    $color_index = 0;

    foreach ($role_count as $role => $count) {
      $results[] = [
        'name' => ucfirst($role),
        'count' => $count,
        'value' => round(($count / count($users)) * 100, 2),
        'color' => $this->colors[$color_index++],
      ];
    }

    return $results;
  }
}
