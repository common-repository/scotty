<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\PostsTrait;
use NSScottyPlugin\Traits\PostMetaTrait;
use NSScottyPlugin\Traits\CommentsTrait;
use NSScottyPlugin\Traits\OptionsTrait;
use NSScottyPlugin\Traits\CommentMetaTrait;
use NSScottyPlugin\Traits\UserMetaTrait;
use NSScottyPlugin\Traits\TermMetaTrait;
use NSScottyPlugin\Traits\TermRelationshipsTrait;
use NSScottyPlugin\Traits\TermsTrait;
use NSScottyPlugin\Traits\UserTrait;

class OverviewAjaxServiceProvider extends AjaxServiceProvider
{
  use PostsTrait,
    PostMetaTrait,
    CommentsTrait,
    OptionsTrait,
    CommentMetaTrait,
    UserMetaTrait,
    TermMetaTrait,
    TermRelationshipsTrait,
    TermsTrait,
    UserTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = ['overview'];

  /**
   * Get the list of duplicates.
   */
  public function overview()
  {
    $posts_count = (int) $this->getPostsCount();
    $posts_per_status = $this->getPostsCountPerStatus();
    $posts_per_type = $this->getPostsCountPerType();

    $commnets_count = (int) $this->getCommentsCount();
    $comments_per_approved = $this->getCommentsCountPerApproved();

    $options_count = (int) $this->getOptionsCount();
    $options_transients = (int) $this->getTransientOptionsCount();
    $options_others = $options_count - $options_transients;

    $users_count = (int) $this->getUsersCount();
    $users_per_role = $this->getUsersCountPerRole();

    wp_send_json([
      'posts_status' => [
        'count' => $posts_count,
        'data' => $posts_per_status,
      ],
      'posts_type' => [
        'count' => $posts_count,
        'data' => $posts_per_type,
      ],
      'comments' => [
        'count' => $commnets_count,
        'data' => $comments_per_approved,
      ],
      'users' => [
        'count' => $users_count,
        'data' => $users_per_role,
      ],
      'options' => [
        'count' => $options_count,
        'data' => [
          [
            'name' => __('Transients', 'scotty'),
            'value' => round(($options_transients / $options_count) * 100, 2),
            'count' => $options_transients,
            'color' => 'blue.6',
          ],
          [
            'name' => __('Others', 'scotty'),
            'value' => round(($options_others / $options_count) * 100, 2),
            'count' => $options_others,
            'color' => 'gray.2',
          ],
        ],
      ],
    ]);
  }
}
