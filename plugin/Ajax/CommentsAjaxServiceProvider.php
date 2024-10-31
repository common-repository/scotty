<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\CommentsTrait;

class CommentsAjaxServiceProvider extends AjaxServiceProvider
{
  use CommentsTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = [
    'unapproved_comments',
    'delete_unapproved_comments_with_id',
    'delete_spam_comments_with_id',
    'delete_deleted_comments_with_id',
    'spam_comments',
    'deleted_comments'
  ];

  /**
   * Return the data and columns in JSON format.
   *
   * @param array $results
   * @return void
   */
  public function returnColumnsJson($results)
  {
    $data = [];
    if ($results) {
      foreach ($results as $record) {
        $data[] = [
          'id' => $record->comment_ID,
          'author' => $record->comment_author,
          'date' => $record->comment_date,
          'content' => $record->comment_content,
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [['accessor' => 'author'], ['accessor' => 'date'], ['accessor' => 'content', 'width' => 300, 'ellipsis' => true, 'noWrap' => true]],
    ];

    wp_send_json($json);
  }

  /**
   * Get the list of unapproved comments for Ajax
   *
   * @return void
   */
  public function unapproved_comments()
  {
    $results = $this->getUnapprovedComments();

    $this->returnColumnsJson($results);
  }

  public function delete_unapproved_comments_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    wp_delete_comment((int) $id, true);
  }

  public function delete_spam_comments_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    wp_delete_comment((int) $id, true);
  }

  public function delete_deleted_comments_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    wp_delete_comment((int) $id, true);
  }


  /**
   * Get the list of spam comments for Ajax
   *
   * @return void
   */
  public function spam_comments()
  {
    $results = $this->getSpamComments();

    $this->returnColumnsJson($results);
  }

  /**
   * Get the list of deleted comments for Ajax
   *
   * @return void
   */
  public function deleted_comments()
  {
    $results = $this->getDeletedComments();

    $this->returnColumnsJson($results);
  }
}
