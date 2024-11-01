<?php if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly?>

<?php
if (isset($_GET['tab'])) {
    $tab = trim(strip_tags(htmlspecialchars($_GET['tab'])));
}
?>

<div class="wrap admin-homepage">
    <h1><?php \SnapWizard\Helpers\Intl::i_esc_html_e(get_admin_page_title()); ?></h1>

    <?php // WordPress provides the styling for tabs?>
    <h2 class="nav-tab-wrapper">
        <?php include('include' . DIRECTORY_SEPARATOR . 'tabs.php'); ?>
    </h2>

    <?php if (!get_option('permalink_structure')): ?>
        <div class="notice notice-error">
            <?php \SnapWizard\Helpers\Intl::i_esc_html_e('Please, change the Permalink structure from Plain to something else in order to allow WordPress APIs working correctly!'); ?>
            <a href="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(admin_url()); ?>options-permalink.php">
                <?php \SnapWizard\Helpers\Intl::i_esc_html_e('Go to permalink page'); ?>
            </a>
        </div>
    <?php else: ?>
        <form action="options.php" method="post" id="snapwizard_main_form" name="snapwizard_main_form">
            <?php

            switch ($tab) {
                case \SnapWizard\Helpers\Constants::SNAPWIZARD_IG_SETTINGS_TAB:
                    settings_fields(\SnapWizard\Helpers\Constants::SNAPWIZARD_IG_SETTINGS_GROUP);

                    do_settings_sections(\SnapWizard\Helpers\Constants::SNAPWIZARD_IG_SETTINGS_PAGE);

                    break;
                case \SnapWizard\Helpers\Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_TAB:
                    settings_fields(\SnapWizard\Helpers\Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_GROUP);

                    do_settings_sections(\SnapWizard\Helpers\Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_PAGE);

                    break;
                case \SnapWizard\Helpers\Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_TAB:
                    settings_fields(\SnapWizard\Helpers\Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_GROUP);

                    do_settings_sections(\SnapWizard\Helpers\Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_PAGE);

                    break;
                case \SnapWizard\Helpers\Constants::SNAPWIZARD_STATS_TAB:
                    settings_fields(\SnapWizard\Helpers\Constants::SNAPWIZARD_STATS_GROUP);

                    do_settings_sections(\SnapWizard\Helpers\Constants::SNAPWIZARD_STATS_PAGE);

                    break;
                case \SnapWizard\Helpers\Constants::SNAPWIZARD_DOCUMENTATION_TAB:
                    settings_fields(\SnapWizard\Helpers\Constants::SNAPWIZARD_DOCUMENTATION_GROUP);

                    do_settings_sections(\SnapWizard\Helpers\Constants::SNAPWIZARD_DOCUMENTATION_PAGE);

                    break;
                default:
                    settings_fields(\SnapWizard\Helpers\Constants::SNAPWIZARD_WELCOME_GROUP);

                    do_settings_sections(\SnapWizard\Helpers\Constants::SNAPWIZARD_WELCOME_PAGE);

                    break;
            }

        submit_button();
        ?>
        </form>
    <?php endif; ?>
</div>

<?php include('include' . DIRECTORY_SEPARATOR . 'contributors.php'); ?>
