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

class DuplicatesAjaxServiceProvider extends AjaxServiceProvider
{
    use PostsTrait, PostMetaTrait, CommentsTrait, OptionsTrait, CommentMetaTrait, UserMetaTrait, TermMetaTrait, TermRelationshipsTrait, TermsTrait;

    /**
     * List of the ajax actions executed only by logged-in users.
     * Here you will use a methods list.
     *
     * @var array
     */
    protected $logged = ['duplicates'];

    /**
     * Get the list of duplicates.
     */
    public function duplicates()
    {
        $postmeta = $this->getDuplicatePostMetaCount();
        $commentmeta = $this->getDuplicateCommentMetaCount();
        $usermeta = $this->getDuplicateUserMetaCount();
        $termmeta = $this->getDuplicateTermMetaCount();

        wp_send_json([
          ['id' => 'duplicate_postmeta', 'count' => $postmeta, 'label' => __('PostMeta', 'scotty')],
          ['id' => 'duplicate_commentmeta', 'count' => $commentmeta, 'label' => __('CommentMeta', 'scotty')],
          ['id' => 'duplicate_usermeta', 'count' => $usermeta, 'label' => __('UserMeta', 'scotty')],
          ['id' => 'duplicate_termmeta', 'count' => $termmeta, 'label' => __('TermMeta', 'scotty')],
        ]);
    }
}
