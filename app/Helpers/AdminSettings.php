<?php

namespace SnapWizard\Helpers;

use Parsedown;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplayException;
use Exception;

class AdminSettings
{
    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_welcome_description_field(array $args): View
    {
        return new View('welcome_fields', [
            'args' => $args,
            'field' => 'description',
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_welcome_how_to_field(array $args): View
    {
        return new View('welcome_fields', [
            'args' => $args,
            'field' => 'how_to',
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     * @throws InstagramBasicDisplayException
     */
    public function snapwizard_setting_token_field(array $args): View
    {
        $snapWizardAppSecret = get_option('snapwizard_app_secret');
        $snapWizardAppId = get_option('snapwizard_app_id');
        $snapWizardRedirectUrl = get_option('snapwizard_redirect_url');

        $instagram = null;
        if ($snapWizardAppSecret && $snapWizardAppId && $snapWizardRedirectUrl) {
            $instagram = new InstagramBasicDisplay([
                'appId'         => $snapWizardAppId,
                'appSecret'     => $snapWizardAppSecret,
                'redirectUri'   => $snapWizardRedirectUrl
            ]);
        }

        $appIdFromUrl = filter_input(INPUT_GET, "delete_token", FILTER_SANITIZE_NUMBER_INT);
        if ($instagram && $appIdFromUrl) {
            if ($appIdFromUrl === $snapWizardAppId) {
                delete_option('snapwizard_crypted_token');
            }
        }

        // Get the value of the setting we've registered with register_setting()
        $snapWizardToken = get_option('snapwizard_crypted_token');

        return new View('snapwizard-settings_fields', [
            'snapWizardToken' => $snapWizardToken,
            'instagram' => $instagram,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_secret_key_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapWizardSecretKey = get_option('snapwizard_secret_key');

        return new View('snapwizard-settings_fields', [
            'snapWizardSecretKey' => $snapWizardSecretKey
                ? $snapWizardSecretKey
                : str_shuffle(Constants::SNAPWIZARD_SECRET_KEY_DEFAULT . substr(str_shuffle(preg_replace("/[^A-zÀ-ú0-9]+/", "", get_bloginfo('name'))), 0, 7)),
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_app_id_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapWizardAppId = get_option('snapwizard_app_id');

        return new View('snapwizard-settings_fields', [
            'snapWizardAppId' => $snapWizardAppId,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_app_secret_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapWizardAppSecret = get_option('snapwizard_app_secret');

        return new View('snapwizard-settings_fields', [
            'snapWizardAppSecret' => $snapWizardAppSecret,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_redirect_url_field(array $args): View
    {
        // Get the value of the setting
        $snapWizardRedirectUrl = sprintf(
            '%s%s%s%s',
            site_url('/', 'https'),
            'wp-json/',
            Constants::SNAPWIZARD_API_NAMESPACE,
            Constants::SNAPWIZARD_API_AUTH
        );
        //get_option( 'snapwizard_redirect_url' );

        return new View('snapwizard-settings_fields', [
            'snapWizardRedirectUrl' => $snapWizardRedirectUrl,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_process_ig_feed_field(array $args): View
    {
        $snapWizardToken = get_option('snapwizard_crypted_token');

        $loggedIn = false;
        if ($snapWizardToken) {
            $loggedIn = true;
        }

        // Get the value of the setting
        $snapWizardProcessIGFeedUrl = sprintf(
            '%s%s%s%s?appid=%s',
            site_url('/', 'https'),
            'wp-json/',
            Constants::SNAPWIZARD_API_NAMESPACE,
            Constants::SNAPWIZARD_API_PROCESS,
            get_option('snapwizard_app_id')
        );

        return new View('snapwizard-settings_fields', [
            'loggedIn' => $loggedIn,
            'snapWizardProcessIGFeedUrl' => $snapWizardProcessIGFeedUrl,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    //    public function snapwizard_setting_processing_type_field( array $args ): View
    //    {
    //        // Get the value of the setting we've registered with register_setting()
    //        $snapWizardProcessingType = get_option( 'snapwizard_processing_type' );
    //
    //        return new View('snapwizard-settings_fields', [
    //            'snapWizardProcessingType' => $snapWizardProcessingType,
    //            'args' => $args,
    //        ]);
    //    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_file_type_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapWizardFileType = get_option('snapwizard_file_type');

        return new View('feed_processor_settings_fields', [
            'snapWizardFileType' => $snapWizardFileType,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_author_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapwizardAuthor = get_option('snapwizard_author');
        $snapwizardAuthorsAll = $this->getAllAuthors();

        return new View('snap_in_wordpress_fields', [
            'snapwizardAuthor' => $snapwizardAuthor,
            'snapwizardAuthorsAll' => $snapwizardAuthorsAll,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_post_categories_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapwizardPostCategories = get_option('snapwizard_post_categories');
        $snapwizardPostCategoriesAll = $this->getCategories();

        return new View('snap_in_wordpress_fields', [
            'snapwizardPostCategories' => $snapwizardPostCategories,
            'snapwizardPostCategoriesAll' => $snapwizardPostCategoriesAll,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_media_categories_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapwizardMediaCategories = get_option('snapwizard_media_categories');
        $snapwizardMediaCategoriesAll = $this->getCategories();

        return new View('snap_in_wordpress_fields', [
            'snapwizardMediaCategories' => $snapwizardMediaCategories,
            'snapwizardMediaCategoriesAll' => $snapwizardMediaCategoriesAll,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_documentation_field(array $args): View
    {
        $mdContent = file_get_contents(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . SNAPWIZARD_SLUG . DIRECTORY_SEPARATOR . 'DOC.md');

        $doc = (new Parsedown())->text($mdContent);

        return new View('documentation_fields', [
            'doc' => $doc,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_limit_per_page_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapWizardLimitPerPage = get_option('snapwizard_limit_per_page');

        return new View('feed_processor_settings_fields', [
            'snapWizardLimitPerPage' => $snapWizardLimitPerPage,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_exclude_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapWizardExclude = get_option('snapwizard_exclude');

        return new View('feed_processor_settings_fields', [
            'snapWizardExclude' => $snapWizardExclude,
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_stats_last_run_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapWizardLastRun = get_option('snapwizard_last_run');

        return new View('stats_fields', [
            'snapWizardLastRun' => ($snapWizardLastRun) ?: 'N.A.',
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_stats_computations_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapWizardComputations = get_option('snapwizard_computations_ms');

        return new View('stats_fields', [
            'snapWizardComputations' => ($snapWizardComputations) ?: 'N.A.',
            'args' => $args,
        ]);
    }

    /**
     * @param array $args
     *
     * @return View
     * @throws Exception
     */
    public function snapwizard_setting_stats_system_calls_field(array $args): View
    {
        // Get the value of the setting we've registered with register_setting()
        $snapWizardSystemCalls = get_option('snapwizard_system_calls_ms');

        return new View('stats_fields', [
            'snapWizardSystemCalls' => ($snapWizardSystemCalls) ?: 'N.A.',
            'args' => $args,
        ]);
    }

    /**
     * @return array
     */
    private function getAllAuthors(): array
    {
        $args = [
            'orderby' => 'display_name',
            'order' => 'DESC',
            'capability' => ['edit_posts'],
        ];

        // Capability queries were only introduced in WP 5.9.
        if (version_compare($GLOBALS['wp_version'], '5.9', '<')) {
            $args['who'] = 'authors';
            unset($args['capability']);
        }

        // Get the results
        return get_users($args);
    }

    /**
     * @return array
     */
    private function getCategories(): array
    {
        $args = [
            'orderby' => 'name',
            'hide_empty' => false,
        ];

        return get_categories($args);
    }
}
