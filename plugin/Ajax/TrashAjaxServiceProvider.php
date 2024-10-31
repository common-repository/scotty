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

class TrashAjaxServiceProvider extends AjaxServiceProvider
{
    use PostsTrait,
        PostMetaTrait,
        CommentsTrait,
        OptionsTrait,
        CommentMetaTrait,
        UserMetaTrait,
        TermMetaTrait,
        TermRelationshipsTrait,
        TermsTrait;

    /**
     * List of the ajax actions executed only by logged-in users.
     * Here you will use a methods list.
     *
     * @var array
     */
    protected $logged = ['trash'];

    /**
     * Get the list of trash items.
     */
    public function trash()
    {
        $revisions = $this->getPostsCountWithType('revision');
        $auto_draft = $this->getPostsCountWithStatus('auto-draft');
        $deleted_posts = $this->getPostsCountWithStatus('trash');
        $unapproved_comments = $this->getUnapprovedCommentsCount();
        $spam_comments = $this->getSpamCommentsCount();
        $deleted_comments = $this->getDeletedCommentsCount();
        $orphan_postmeta = $this->getOrphanPostMetaCount();
        $transient_options = $this->getTransientOptionsCount();
        $orphan_commentmeta = $this->getOrphanCommentMetaCount();
        $orphan_usermeta = $this->getOrphanUserMetaCount();
        $orphan_termmeta = $this->getOrphanTermMetaCount();
        $orphan_term_relationships = $this->getOrphanTermRelationshipsCount();
        $unused_terms = $this->getUnusedTermsCount();
        $oembed_postmeta = $this->getOEmbedPostMetaCount();

        wp_send_json([
          [
            'id' => 'revisions',
            'count' => $revisions,
            'label' => __('Revisions', 'scotty'),
          ],
          [
            'id' => 'auto_draft',
            'count' => $auto_draft,
            'label' => __('Auto Draft', 'scotty'),
          ],
          [
            'id' => 'deleted_posts',
            'count' => $deleted_posts,
            'label' => __('Deleted Posts', 'scotty'),
          ],
          [
            'id' => 'unapproved_comments',
            'count' => $unapproved_comments,
            'label' => __('Unapproved Comments', 'scotty'),
          ],
          [
            'id' => 'spam_comments',
            'count' => $spam_comments,
            'label' => __('Spam Comments', 'scotty'),
          ],
          [
            'id' => 'deleted_comments',
            'count' => $deleted_comments,
            'label' => __('Deleted Comments', 'scotty'),
          ],
          [
            'id' => 'orphan_commentmeta',
            'count' => $orphan_commentmeta,
            'label' => __('Orphan Comment Meta', 'scotty'),
          ],
          [
            'id' => 'transient_options',
            'count' => $transient_options,
            'label' => __('Transient Options', 'scotty'),
          ],
          [
            'id' => 'orphan_postmeta',
            'count' => $orphan_postmeta,
            'label' => __('Orphan Post Meta', 'scotty'),
          ],
          [
            'id' => 'orphan_usermeta',
            'count' => $orphan_usermeta,
            'label' => __('Orphan User Meta', 'scotty'),
          ],
          [
            'id' => 'orphan_termmeta',
            'count' => $orphan_termmeta,
            'label' => __('Orphan Term Meta', 'scotty'),
          ],
          [
            'id' => 'orphan_term_relationships',
            'count' => $orphan_term_relationships,
            'label' => __('Orphan Term Relationships', 'scotty'),
          ],
          [
            'id' => 'unused_terms',
            'count' => $unused_terms,
            'label' => __('Unused Terms', 'scotty'),
          ],
          [
            'id' => 'oembed_postmeta',
            'count' => $oembed_postmeta,
            'label' => __('Oembed Post Meta', 'scotty'),
          ],
        ]);
    }
}
