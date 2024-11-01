<?php

namespace SnapWizard\Services;

use WP_Error;
use SnapWizard\Helpers\Constants;
use SnapWizard\Helpers\Logger;

class WordPressBridgeService
{
    public Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param object $media
     * @param string $postTitle
     * @param int $snapwizardAuthor
     * @param array $snapwizardPostCategories
     * @return int
     */
    public function insertPost(object $media, string $postTitle, int $snapwizardAuthor, array $snapwizardPostCategories = []): int
    {
        $wpPostData = [
            'post_title' => trim($postTitle),
            'post_content' => (isset($media->caption)) ? sprintf('%s <br /><a href="%s" target="_blank">%s</a>', $media->caption, $media->permalink, __('Read on Instagram')) : '',
            'post_type' => 'post', // or whatever is your post type slug.
            'post_author' => $snapwizardAuthor,
            'post_category' => $snapwizardPostCategories,
            'post_status' => 'publish',
            'post_date' => date("Y-m-d H:i:s", strtotime($media->timestamp)),
            'meta_input' => [
                // If you have any meta data, that will go here.
            ],
        ];

        $postId = wp_insert_post($wpPostData, true);

        if(is_wp_error($postId)) {
            return -1;
        }

        return $postId;
    }

    /**
     * @param object $media
     * @param int $snapwizardAuthor
     * @param int $postId
     * @param bool|array $snapwizardMediaCategories
     * @return int|WP_Error
     */
    public function setAttachmentAndThumbnail(object $media, int $snapwizardAuthor, int $postId, bool|array $snapwizardMediaCategories): int|WP_Error
    {
        if (is_wp_error($postId)) {
            return new WP_Error('is_wp_error', 'Error in setAttachment');
        }

        $mediaUrl = $media->media_url ?? $media->thumbnail_url;

        $mediaInfo = pathinfo($mediaUrl); // Extracting information into array

        if ($mediaInfo === '') {
            return new WP_Error('media-info', 'Error in setAttachment');
        }

        $attachId = $this->saveAttachment($mediaUrl, $mediaInfo, $media, $snapwizardAuthor, $postId, $snapwizardMediaCategories);

        // And finally assign featured image to post
        //        if (!has_post_thumbnail($postId)) {
        if (! is_wp_error($attachId)) {
            set_post_thumbnail($postId, $attachId);
        }
        //        }

        // Thumbnail on video
        if ($media->media_type === Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_VIDEO) {
            $mediaUrl = $media->thumbnail_url;

            $mediaInfo = pathinfo($mediaUrl); // Extracting information into array

            $attachVideoId = $this->saveAttachment($mediaUrl, $mediaInfo, $media, $snapwizardAuthor, $postId, $snapwizardMediaCategories);

            if (! is_wp_error($attachVideoId)) {
                set_post_thumbnail($postId, $attachVideoId);
            }
        }

        return $attachId;
    }

    /**
     * @param mixed $media
     * @param int $attachmentId
     * @param array $galleryVideos
     * @param array $galleryIds
     * @return array[]
     */
    public function prepareGalleries(mixed $media, int $attachmentId, array $galleryVideos, array $galleryIds): array
    {
        if ($media->media_type === Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_VIDEO) {
            if (is_int($attachmentId)) {
                $galleryVideos[] = '[video src="' . wp_get_attachment_url($attachmentId) . '"]';
            }
        } elseif ($media->media_type === Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_IMAGE) {
            if (is_int($attachmentId)) {
                $galleryIds[] = $attachmentId;
            }
        }

        return [$galleryVideos, $galleryIds];
    }

    /**
     * @param array $galleryVideos
     * @param array $galleryIds
     * @param int $postId
     */
    public function appendGalleriesToPostContent(array $galleryVideos, array $galleryIds, int $postId): void
    {
        // VIDEO GALLERY
        if (count($galleryVideos) > 0) {

            $videoGallery = implode('', $galleryVideos);

            $lastPostInserted = get_post($postId);

            wp_update_post([
                'ID' => $postId,
                'post_content' => $lastPostInserted->post_content . $videoGallery,
            ]);

            return;
        }

        if (count($galleryIds) > 0) {
            // GALLERY
            $gallery = ' [gallery ids="' . implode(',', $galleryIds) . '" columns="4" size="medium" link="file"]';

            $lastPostInserted = get_post($postId);

            wp_update_post([
                'ID' => $postId,
                'post_content' => $lastPostInserted->post_content . $gallery,
            ]);

            return;
        }
    }

    /**
     * @param string $mediaUrl
     * @param array|string $mediaInfo
     * @param object $media
     * @param int $snapwizardAuthor
     * @param int $postId
     * @param bool|array $snapwizardMediaCategories
     * @return int|WP_Error
     */
    private function saveAttachment(string $mediaUrl, array|string $mediaInfo, object $media, int $snapwizardAuthor, int $postId, bool|array $snapwizardMediaCategories): int|WP_Error
    {
        list($filename, $file) = $this->getExtendedFilename($mediaUrl, $mediaInfo);

        clearstatcache(true, $file);

        // Create the image file on the server
        $content = wp_remote_get($mediaUrl);
        if (is_wp_error($content)) {
            $WP_Error = new WP_Error();
            $WP_Error->add('my_error', '<strong>Error</strong>: Cannot retrieve the following file: ' . $mediaUrl);

            return $WP_Error;
        }
        $contentBody = $content['body'];
        //        $contentData = $content['headers']->getAll();

        if (!file_put_contents($file, $contentBody)) {
            $WP_Error = new WP_Error();
            $WP_Error->add('my_error', '<strong>Error</strong>: Cannot save the file!');

            return $WP_Error;
        }

        // Include media and image library
        require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'media.php';
        require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'image.php';

        // Set attachment data
        $args = [
            'post_mime_type' => wp_check_filetype($filename, null)['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => $media->caption ?? '',
            'post_status' => 'inherit',
            'post_author' => $snapwizardAuthor,
            'post_date' => date("Y-m-d H:i:s", strtotime($media->timestamp)),
        ];

        // Create the attachment
        $attachId = wp_insert_attachment($args, $file, $postId);

        if ($snapwizardMediaCategories) {
            wp_set_object_terms($attachId, $snapwizardMediaCategories, 'category');
        }

        // Define attachment metadata
        $attachData = wp_generate_attachment_metadata($attachId, $file);

        // Assign metadata to attachment
        wp_update_attachment_metadata($attachId, $attachData);

        return $attachId;
    }

    /**
     * @param string $mediaUrl
     * @param array|string $mediaInfo
     * @return array
     */
    private function getExtendedFilename(string $mediaUrl, array|string $mediaInfo): array
    {
        $filename = parse_url($mediaInfo['basename'], PHP_URL_PATH);

        // Check folder permission and define file location
        $file = $this->getFilePathBasedOnPermissions($filename);

        // Check mime type and extension
        $file = $this->setExtensionIfNecessary($mediaInfo, $mediaUrl, $file);

        return array($filename, $file);
    }

    /**
     * @param string $filename
     * @return string
     */
    private function getFilePathBasedOnPermissions(string $filename): string
    {
        if (wp_mkdir_p(wp_get_upload_dir()['path'])) {
            return wp_get_upload_dir()['path'] . '/' . $filename;
        }

        return wp_get_upload_dir()['basedir'] . '/' . $filename;
    }

    /**
     * @param array|string $mediaInfo
     * @param string $mediaUrl
     * @param string $file
     * @return string
     */
    private function setExtensionIfNecessary(array|string $mediaInfo, string $mediaUrl, string $file): string
    {
        if ($mediaInfo['extension'] === '' || strlen($mediaInfo['extension']) > 5) {
            $extension = $this->mime2ext($mediaUrl);

            if ($extension !== false) {
                if (!str_contains($file, $extension)) {
                    $file = $file . '.' . $extension;
                }
            }

            if (WP_DEBUG) {
                $this->logger->info(
                    'Check extension',
                    [
                        'file' => $file,
                        'mediaUrl' => $mediaUrl,
                        'mediaInfo' => $mediaInfo,
                        'media-extension' => $mediaInfo['extension'],
                        'extension' => $extension
                    ]
                );
            }
        }

        return $file;
    }

    /**
     * @param string $url
     * @return bool|string
     */
    private function mime2ext(string $url): bool|string
    {
        $content = wp_remote_get($url);
        if (is_wp_error($content)) {
            return false;
        }
        $contentData = $content['headers']->getAll();
        $mime = $contentData['content-type'];

        $videoMimeMap = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'video/x-f4v'                                                               => 'f4v',
            'video/x-flv'                                                               => 'flv',
            'video/mj2'                                                                 => 'jp2',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'video/ogg'                                                                 => 'ogg',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'video/webm'                                                                => 'webm',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
        ];

        return $videoMimeMap[$mime] ?? false;
    }
}
