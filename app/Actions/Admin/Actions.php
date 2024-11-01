<?php

namespace SnapWizard\Actions\Admin;

use Exception;
use SnapWizard\Helpers\View;
use SnapWizard\Helpers\Field;
use SnapWizard\Helpers\AdminSettings;
use SnapWizard\Helpers\Constants;

class Actions
{
    public Field $field;
    public AdminSettings $adminSettings;

    public string $side = 'admin';

    public function __construct(Field $field, AdminSettings $adminSettings)
    {
        $this->field = $field;
        $this->adminSettings = $adminSettings;

        add_action('admin_init', [$this, 'snapWizard_register_script']);
        add_action('admin_enqueue_scripts', [$this, 'snapWizard_admin_enqueue_scripts']);

        add_action('admin_menu', [$this, 'create_snapWizard_admin_menu']);
        add_action('admin_init', [$this, 'create_snapWizard_admin_page_settings']);

        // TODO ... doble check on init or admin_init
        add_action('admin_init', [$this, 'snapWizard_wp_add_categories_to_attachments']);
        add_action('init', [$this, 'snapWizard_wp_add_categories_to_attachments']);
    }

    public function snapWizard_wp_add_categories_to_attachments(): void
    {
        register_taxonomy_for_object_type('category', 'attachment');
    }

    public function snapWizard_register_script(): void
    {
        wp_register_script(
            'snapwizard_custom_js',
            plugins_url(SNAPWIZARD_SLUG . '/resources/views/admin/assets/js/snapwizard_custom.js'),
            [],
            '1.0.5',
        );

        wp_register_style(
            'snapwizard_custom_css',
            plugins_url(SNAPWIZARD_SLUG . '/resources/views/admin/assets/css/snapwizard_custom.css'),
            false,
            '1.0.5',
            'all'
        );
    }

    public function snapWizard_admin_enqueue_scripts(): void
    {
        wp_enqueue_script('snapwizard_custom_js');

        wp_enqueue_style('snapwizard_custom_css');
    }

    public function create_snapWizard_admin_menu(): void
    {
        add_menu_page(
            'SnapWizard Admin page',
            'SnapWizard',
            'manage_options',
            Constants::SNAPWIZARD_MENU_SLUG,
            [$this, 'show_snapWizard_admin_menu'],
            'dashicons-instagram',
            // 6
        );
    }

    /**
     * @return View
     * @throws Exception
     */
    public function show_snapWizard_admin_menu(): View
    {
        return new View('admin-configuration', [
            'tab' => 'welcome',
        ]);
    }

    public function create_snapWizard_admin_page_settings(): void
    {
        add_settings_section(
            Constants::SNAPWIZARD_WELCOME_SECTION,
            '', //'<span class="dashicons-before dashicons-instagram"></span>' . __( 'Instagram Settings' ),
            false, //[ $this, 'snapwizard_config_section_info' ],
            Constants::SNAPWIZARD_WELCOME_PAGE,
            []
        );

        $this->addSettingsWelcomeDescription();
        $this->addSettingsWelcomeHowTo();

        add_settings_section(
            Constants::SNAPWIZARD_IG_SETTINGS_SECTION,
            '', //'<span class="dashicons dashicons-admin-settings"></span>' . __( 'Feed Processor settings' ),
            false, //[ $this, 'snapwizard_config_section_info' ],
            Constants::SNAPWIZARD_IG_SETTINGS_PAGE,
            []
        );

        $this->addSettingsFieldToken();
        $this->addSettingsFieldAppId();
        $this->addSettingsFieldAppSecret();
        $this->addSettingsFieldSecretKey();
        $this->addSettingsFieldRedirectUrl();
        $this->addSettingsFieldProcessIGFeedUrl();

        add_settings_section(
            Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_SECTION,
            '', //'<span class="dashicons dashicons-wordpress"></span>' . __( 'Snap your feed in WordPress' ),
            false, //[ $this, 'snapwizard_config_section_info' ],
            Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_PAGE,
            []
        );

        //        $this->addSettingsFieldProcessingType();
        $this->addSettingsFieldLimitPerPage();
        $this->addSettingsFieldFileType();
        $this->addSettingsFieldExclude();

        add_settings_section(
            Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_SECTION,
            '', //'<span class="dashicons dashicons-wordpress"></span>' . __( 'Snap your feed in WordPress' ),
            false, //[ $this, 'snapwizard_config_section_info' ],
            Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_PAGE,
            []
        );

        $this->addSettingsFieldAuthor();
        $this->addSettingsFieldPostCategories();
        $this->addSettingsFieldMediaCategories();

        add_settings_section(
            Constants::SNAPWIZARD_STATS_SECTION,
            '', //'<span class="dashicons dashicons-wordpress"></span>' . __( 'Snap your feed in WordPress' ),
            false, //[ $this, 'snapwizard_config_section_info' ],
            Constants::SNAPWIZARD_STATS_PAGE,
            []
        );

        $this->addSettingsFieldStatsLastRun();
        $this->addSettingsFieldStatsComputations();
        $this->addSettingsFieldStatsSystemCalls();

        add_settings_section(
            Constants::SNAPWIZARD_DOCUMENTATION_SECTION,
            '', //'<span class="dashicons dashicons-wordpress"></span>' . __( 'Snap your feed in WordPress' ),
            false, //[ $this, 'snapwizard_config_section_info' ],
            Constants::SNAPWIZARD_DOCUMENTATION_PAGE,
            []
        );

        $this->addSettingsFieldDocumentation();
    }

    //    function snapwizard_config_section_title() {
    //        echo '<p>Reports</p>';
    //    }

    /**
     * @return void
     */
    private function addSettingsWelcomeDescription(): void
    {
        $this->field->addSettingsField('snapwizard_welcome_description_field', 'Description', [
            $this->adminSettings,
            'snapwizard_welcome_description_field'
        ], Constants::SNAPWIZARD_WELCOME_PAGE, Constants::SNAPWIZARD_WELCOME_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_WELCOME_GROUP, 'snapwizard_welcome_description');
    }

    private function addSettingsWelcomeHowTo(): void
    {
        $this->field->addSettingsField('snapwizard_welcome_how_to_field', 'How to', [
            $this->adminSettings,
            'snapwizard_welcome_how_to_field'
        ], Constants::SNAPWIZARD_WELCOME_PAGE, Constants::SNAPWIZARD_WELCOME_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_WELCOME_GROUP, 'snapwizard_welcome_how_to');
    }

    /**
     * @return void
     */
    private function addSettingsFieldToken(): void
    {
        $this->field->addSettingsField('snapwizard_token_settings_field', 'Token', [
            $this->adminSettings,
            'snapwizard_setting_token_field'
        ], Constants::SNAPWIZARD_IG_SETTINGS_PAGE, Constants::SNAPWIZARD_IG_SETTINGS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_IG_SETTINGS_GROUP, 'snapwizard_token');
    }

    /**
     * @return void
     */
    private function addSettingsFieldSecretKey(): void
    {
        $this->field->addSettingsField('snapwizard_secret_key_settings_field', 'Secret Key', [
            $this->adminSettings,
            'snapwizard_setting_secret_key_field'
        ], Constants::SNAPWIZARD_IG_SETTINGS_PAGE, Constants::SNAPWIZARD_IG_SETTINGS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_IG_SETTINGS_GROUP, 'snapwizard_secret_key');
    }

    /**
     * @return void
     */
    private function addSettingsFieldAppId(): void
    {
        $this->field->addSettingsField('snapwizard_app_id_settings_field', 'App ID', [
            $this->adminSettings,
            'snapwizard_setting_app_id_field'
        ], Constants::SNAPWIZARD_IG_SETTINGS_PAGE, Constants::SNAPWIZARD_IG_SETTINGS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_IG_SETTINGS_GROUP, 'snapwizard_app_id');
    }

    /**
     * @return void
     */
    private function addSettingsFieldAppSecret(): void
    {
        $this->field->addSettingsField('snapwizard_app_secret_settings_field', 'App Secret', [
            $this->adminSettings,
            'snapwizard_setting_app_secret_field'
        ], Constants::SNAPWIZARD_IG_SETTINGS_PAGE, Constants::SNAPWIZARD_IG_SETTINGS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_IG_SETTINGS_GROUP, 'snapwizard_app_secret');
    }

    /**
     * @return void
     */
    private function addSettingsFieldRedirectUrl(): void
    {
        $this->field->addSettingsField('snapwizard_redirect_url_settings_field', 'OAuth Redirect URL', [
            $this->adminSettings,
            'snapwizard_setting_redirect_url_field'
        ], Constants::SNAPWIZARD_IG_SETTINGS_PAGE, Constants::SNAPWIZARD_IG_SETTINGS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_IG_SETTINGS_GROUP, 'snapwizard_redirect_url');
    }

    /**
     * @return void
     */
    private function addSettingsFieldProcessIGFeedUrl(): void
    {
        $this->field->addSettingsField('snapwizard_process_ig_feed_settings_field', 'Feed Processor Endpoint', [
            $this->adminSettings,
            'snapwizard_setting_process_ig_feed_field'
        ], Constants::SNAPWIZARD_IG_SETTINGS_PAGE, Constants::SNAPWIZARD_IG_SETTINGS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_IG_SETTINGS_GROUP, 'snapwizard_process_ig_feed_url');
    }

    /**
     * @return void
     */
    //    private function addSettingsFieldProcessingType(): void
    //    {
    //        $this->field->addSettingsField('snapwizard_processing_type_settings_field', 'Processing type', [$this->adminSettings, 'snapwizard_setting_processing_type_field'], Constants::SNAPWIZARD_MENU_SLUG, Constants::SNAPWIZARD_CUSTOM_SECTION);
    //        $this->field->registerSetting(Constants::SNAPWIZARD_SETTINGS_GROUP,'snapwizard_processing_type');
    //    }

    /**
     * @return void
     */
    private function addSettingsFieldFileType(): void
    {
        $this->field->addSettingsField('snapwizard_file_type_settings_field', 'Media type', [
            $this->adminSettings,
            'snapwizard_setting_file_type_field'
        ], Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_PAGE, Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_GROUP, 'snapwizard_file_type');
    }

    /**
     * @return void
     */
    private function addSettingsFieldLimitPerPage(): void
    {
        $this->field->addSettingsField('snapwizard_limit_per_page_settings_field', 'Limit per page', [
            $this->adminSettings,
            'snapwizard_setting_limit_per_page_field'
        ], Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_PAGE, Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_GROUP, 'snapwizard_limit_per_page');
    }

    /**
     * @return void
     */
    private function addSettingsFieldExclude(): void
    {
        $this->field->addSettingsField('snapwizard_exclude_settings_field', 'Exclude (one per line)', [
            $this->adminSettings,
            'snapwizard_setting_exclude_field'
        ], Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_PAGE, Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_GROUP, 'snapwizard_exclude');
    }

    /**
     * @return void
     */
    private function addSettingsFieldAuthor(): void
    {
        $this->field->addSettingsField('snapwizard_author_settings_field', 'Author', [
            $this->adminSettings,
            'snapwizard_setting_author_field'
        ], Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_PAGE, Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_GROUP, 'snapwizard_author');
    }

    /**
     * @return void
     */
    private function addSettingsFieldPostCategories(): void
    {
        $this->field->addSettingsField('snapwizard_post_categories_settings_field', 'Post Categories', [
            $this->adminSettings,
            'snapwizard_setting_post_categories_field'
        ], Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_PAGE, Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_GROUP, 'snapwizard_post_categories');
    }

    /**
     * @return void
     */
    private function addSettingsFieldMediaCategories(): void
    {
        $this->field->addSettingsField('snapwizard_media_categories_settings_field', 'Media Categories', [
            $this->adminSettings,
            'snapwizard_setting_media_categories_field'
        ], Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_PAGE, Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_GROUP, 'snapwizard_media_categories');
    }

    /**
     * @return void
     */
    private function addSettingsFieldStatsLastRun(): void
    {
        $this->field->addSettingsField('snapwizard_stats_last_run_field', 'Last completed run at', [
            $this->adminSettings,
            'snapwizard_setting_stats_last_run_field'
        ], Constants::SNAPWIZARD_STATS_PAGE, Constants::SNAPWIZARD_STATS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_STATS_GROUP, 'snapwizard_stats_last_run');
    }

    /**
     * @return void
     */
    private function addSettingsFieldStatsComputations(): void
    {
        $this->field->addSettingsField('snapwizard_stats_computations_settings_field', 'Computations', [
            $this->adminSettings,
            'snapwizard_setting_stats_computations_field'
        ], Constants::SNAPWIZARD_STATS_PAGE, Constants::SNAPWIZARD_STATS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_STATS_GROUP, 'snapwizard_stats_computations');
    }

    /**
     * @return void
     */
    private function addSettingsFieldStatsSystemCalls(): void
    {
        $this->field->addSettingsField('snapwizard_stats_system_calls_field', 'System Calls', [
            $this->adminSettings,
            'snapwizard_setting_stats_system_calls_field'
        ], Constants::SNAPWIZARD_STATS_PAGE, Constants::SNAPWIZARD_STATS_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_STATS_GROUP, 'snapwizard_stats_system_calls');
    }


    /**
     * @return void
     */
    private function addSettingsFieldDocumentation(): void
    {
        $this->field->addSettingsField('snapwizard_documentation_settings_field', 'Documentation', [
            $this->adminSettings,
            'snapwizard_setting_documentation_field'
        ], Constants::SNAPWIZARD_DOCUMENTATION_PAGE, Constants::SNAPWIZARD_DOCUMENTATION_SECTION);
        $this->field->registerSetting(Constants::SNAPWIZARD_DOCUMENTATION_GROUP, 'snapwizard_documentation');
    }
}
