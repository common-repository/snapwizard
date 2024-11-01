<?php

namespace SnapWizard\Services;

use DateTime;
use SnapWizard\Helpers\Constants;
use SnapWizard\Helpers\Logger;
use WP_REST_Request;
use WP_Query;
use Exception;
use Lablnet\Encryption;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplayException;

class FeedProcessorService
{
    public Logger $logger;
    public TokenManager $tokenManager;
    public WordPressBridgeService $wordPressBridgeService;

    public function __construct(TokenManager $tokenManager, WordPressBridgeService $wordPressBridgeService, Logger $logger)
    {
        $this->logger = $logger;
        $this->tokenManager = $tokenManager;
        $this->wordPressBridgeService = $wordPressBridgeService;
    }

    private function rutime(array|false $rug, array|false $rus, string $index): float|int
    {
        return ($rug["ru_$index.tv_sec"] * 1000 + intval($rug["ru_$index.tv_usec"] / 1000))
            -  ($rus["ru_$index.tv_sec"] * 1000 + intval($rus["ru_$index.tv_usec"] / 1000));
    }

    /**
     * @param WP_REST_Request $request
     * @return string
     *
     * @throws InstagramBasicDisplayException
     * @throws Exception
     */
    public function snapwizard_process_ig_feed_url(WP_REST_Request $request): string
    {
        $rustart = getrusage();

        $this->logger->info('Start processing');

        $snapWizardAppId = get_option('snapwizard_app_id');
        $snapWizardSecretKey = get_option('snapwizard_secret_key');
        $snapWizardToken = get_option('snapwizard_crypted_token');
        $snapWizardLimitPerPage = get_option('snapwizard_limit_per_page');
        $snapWizardExclude = get_option('snapwizard_exclude');
        $snapwizardAuthor = get_option('snapwizard_author');
        $snapwizardPostCategories = get_option('snapwizard_post_categories');
        $snapwizardMediaCategories = get_option('snapwizard_media_categories');
        $snapWizardFileType = get_option('snapwizard_file_type');
        //        $snapWizardProcessingType = get_option( 'snapwizard_processing_type' );
        $snapWizardLastRefreshingToken = get_option('snapwizard_last_refreshing_token');

        $this->logger->info('All settings loaded');

        if (! $snapWizardToken) {
            $this->logger->error('Missing token!');

            return json_encode([
                'response' => 'error',
                'message' => 'Missing token!',
            ]);
        }

        // Security check
        $parameters = $request->get_query_params();
        if ($parameters['appid'] !== $snapWizardAppId) {
            $this->logger->error('App ID mismatch!');

            return json_encode([
                'response' => 'error',
                'message' => 'App ID mismatch!',
            ]);
        }

        $this->logger->info('Security check passed');

        // Decrypt the token
        $encryption = new Encryption($snapWizardSecretKey);
        $decryptedToken = $encryption->decrypt($snapWizardToken);

        $this->logger->info('Token decrypted');

        // IG Basic Display Object
        $instagram = new InstagramBasicDisplay($decryptedToken);

        // Set user access token for the connection
        $instagram->setAccessToken($decryptedToken);

        $this->logger->info('InstagramBasicDisplay called successfully');

        // Get the media from user profile
        $userMedia = $instagram->getUserMedia('me', $snapWizardLimitPerPage);

        $mediaList = $userMedia->data ?? [];
        //echo "<pre>";
        //print_r($userMedia->data);
        //echo "</pre>";
        //echo "<pre>";
        //print_r($userMedia);
        //echo "</pre>";
        //die();

        // caption
        // id
        // media_type
        // media_url
        // permalink
        // thumbnail_url (only for video)
        // timestamp
        // username

        if(isset($userMedia->error)) {
            $this->logger->error($userMedia->error->message);

            return json_encode([
                'response' => 'error',
                'message' => $userMedia->error->message,
            ]);
        }

        // Start pagination
        $this->logger->info('Start pagination calls');

        $paging = true;
        $moreMedia = $instagram->pagination($userMedia);

        $mediaList = array_merge($mediaList, $moreMedia->data);

        if(isset($moreMedia->error)) {
            $this->logger->error($moreMedia->error->message);

            return json_encode([
                'response' => 'error',
                'message' => $moreMedia->error->message,
            ]);
        }

        while ($paging) {
            if (!isset($moreMedia->paging->next)) {
                $paging = false;

                if (WP_DEBUG) {
                    $this->logger->info('Stop pagination calls');
                }

                continue; // Skip the rest of the loop
            }

            $moreMedia = $instagram->pagination($moreMedia);

            if(isset($moreMedia->error)) {
                $this->logger->error($moreMedia->error->message);

                return json_encode([
                    'response' => 'error',
                    'message' => $moreMedia->error->message,
                ]);
            }

            $mediaList = array_merge($mediaList, $moreMedia->data);

            $this->logger->info('Pagination calling...', ['next' => $moreMedia->paging->next ?? null]);
        }

        //        echo "<pre>";
        //        print_r($mediaList);
        //        echo "</pre>";
        //        die();

        // Prepare some values
        $snapWizardExcludeList = explode(PHP_EOL, $snapWizardExclude);

        if ($snapwizardPostCategories === ''
            || $snapwizardPostCategories === false) {
            $snapwizardPostCategories = [];
        }
        if ($snapwizardMediaCategories === ''
            || $snapwizardMediaCategories === false) {
            $snapwizardMediaCategories = [];
        }

        // ============================================
        // START PROCESSING ALL THE MEDIA FROM THE FEED
        // ============================================
        $processed = 0;
        foreach ($mediaList as $media) {
            if ($snapWizardFileType === Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ONLY_IMAGES) {
                if ($media->media_type !== Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_IMAGE) {
                    continue;
                }
            }

            if ($snapWizardFileType === Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ONLY_CAROUSEL_ALBUM) {
                if ($media->media_type !== Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_CAROUSEL_ALBUM) {
                    continue;
                }
            }

            if ($snapWizardFileType === Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ONLY_VIDEOS) {
                if ($media->media_type !== Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_VIDEO) {
                    continue;
                }
            }

            $filename = basename(parse_url(($media->media_url ?? $media->thumbnail_url), PHP_URL_PATH));

            // echo $media->media_url . " ||| " . $originalFilename . "<br />";
            if (count($snapWizardExcludeList) > 0) {
                if (in_array($filename, $snapWizardExcludeList, true)) {
                    continue;
                }
            }

            $this->logger->info('All settings checked');

            //print_r($instpressUploadsDir . DIRECTORY_SEPARATOR . $filename);
            //print_r(file_exists($instpressUploadsDir . DIRECTORY_SEPARATOR . $filename ) ? 'true' : 'false');

            // Process title
            $postTitle = $media->username . ' - ' . $media->id;
            if (isset($media->caption)) {
                $postTitle = substr(strip_tags($media->caption), 0, (strpos(strip_tags($media->caption), PHP_EOL, 0)) - 0); // preg_replace('/([^?!.]*.).*/', '\\1', strip_tags($m->caption));
                if(!$postTitle) {
                    $postTitle = preg_replace('/([^?!.]*.).*/', '\\1', strip_tags($media->caption));
                }
            }

            // Insert post if not exists

            // Retrieve a post with the same name (slug)
            $args = [
                'name' => sanitize_title($postTitle),
                'post_status' => 'publish',
                'post_type' => 'post',
            ];
            $posts = new WP_Query($args);

            $this->logger->info('Check for existing Post Name', ['name' => sanitize_title($postTitle), 'foundPosts' => $posts->found_posts]);

            if ($posts->found_posts === 0) {

                if (WP_DEBUG) {
                    $this->logger->info('Start inserting post...');
                }

                $postId = $this->wordPressBridgeService->insertPost($media, $postTitle, $snapwizardAuthor, $snapwizardPostCategories);

                if (WP_DEBUG) {
                    $this->logger->info('Start inserting media...');
                }

                $attachmentId = $this->wordPressBridgeService->setAttachmentAndThumbnail($media, $snapwizardAuthor, $postId, $snapwizardMediaCategories);

                $galleryIds = [];
                $galleryVideos = [];
                if (isset($media->children)) {

                    if (WP_DEBUG) {
                        $this->logger->info('Loop over children', ['countChildren' => count($media->children->data)]);
                    }

                    foreach ($media->children->data as $mediaChildren) {
                        $attachmentId = $this->wordPressBridgeService->setAttachmentAndThumbnail($mediaChildren, $snapwizardAuthor, $postId, $snapwizardMediaCategories);

                        list($galleryVideos, $galleryIds) = $this->wordPressBridgeService->prepareGalleries($mediaChildren, $attachmentId, $galleryVideos, $galleryIds);
                    }
                }

                if (!isset($media->children)) {

                    if (WP_DEBUG) {
                        $this->logger->info('NO CHILDREN CASE', ['media_type' => $media->media_type]);
                    }

                    list($galleryVideos, $galleryIds) = $this->wordPressBridgeService->prepareGalleries($media, $attachmentId, $galleryVideos, $galleryIds);
                }

                if ($postId > 0) {
                    $this->wordPressBridgeService->appendGalleriesToPostContent($galleryVideos, $galleryIds, $postId);
                }

                $processed++;
            }

            $this->logger->info('---> Element processed <---');
        }

        // Refreshing the token if necessary
        $now = new DateTime();
        $lastRefreshingToken = new DateTime($snapWizardLastRefreshingToken);
        $diff = $now->diff($lastRefreshingToken);

        $this->logger->info('Last refreshing token', ['snapWizardLastRefreshingToken' => $snapWizardLastRefreshingToken]);

        if ($diff->days >= 30) {
            $this->tokenManager->refreshingToken($instagram, $decryptedToken, $snapWizardSecretKey);

            $this->logger->info('Token refreshed');
        }

        // Last log
        $this->logger->info('End');
        $this->logger->info('============================================================================');

        // Stats
        update_option('snapwizard_last_run', date("Y-m-d H:i:s"));

        $getrusage = getrusage();
        update_option('snapwizard_computations_ms', $this->rutime($getrusage, $rustart, "utime"));
        update_option('snapwizard_system_calls_ms', $this->rutime($getrusage, $rustart, "stime"));

        return json_encode([
            'response' => 'success',
            'processed' => sprintf('%d processed', $processed),
        ]);
    }
}
