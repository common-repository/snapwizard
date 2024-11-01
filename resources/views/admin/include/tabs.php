<?php if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly?>

<?php if (get_option('permalink_structure')): ?>
    <a href="?page=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_MENU_SLUG); ?>" class="nav-tab <?php if ($tab === \SnapWizard\Helpers\Constants::SNAPWIZARD_WELCOME_TAB): ?>nav-tab-active<?php endif; ?>">
        <?php esc_html_e(ucfirst(\SnapWizard\Helpers\Constants::SNAPWIZARD_WELCOME_TAB)); ?>
    </a>
    <a href="?page=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_MENU_SLUG); ?>&tab=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_IG_SETTINGS_TAB); ?>" class="nav-tab <?php if ($tab === \SnapWizard\Helpers\Constants::SNAPWIZARD_IG_SETTINGS_TAB): ?>nav-tab-active<?php endif; ?>">
        <?php esc_html_e(ucfirst(\SnapWizard\Helpers\Constants::SNAPWIZARD_IG_SETTINGS_TAB)); ?>
    </a>
    <a href="?page=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_MENU_SLUG); ?>&tab=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_TAB); ?>" class="nav-tab <?php if ($tab === \SnapWizard\Helpers\Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_TAB): ?>nav-tab-active<?php endif; ?>">
        <?php esc_html_e(ucfirst(\SnapWizard\Helpers\Constants::SNAPWIZARD_FEED_PROCESSOR_SETTINGS_TAB)); ?>
    </a>
    <a href="?page=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_MENU_SLUG); ?>&tab=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_TAB); ?>" class="nav-tab <?php if ($tab === \SnapWizard\Helpers\Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_TAB): ?>nav-tab-active<?php endif; ?>">
        <?php esc_html_e(ucfirst(\SnapWizard\Helpers\Constants::SNAPWIZARD_SNAP_IN_WORDPRESS_TAB)); ?>
    </a>
    <a href="?page=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_MENU_SLUG); ?>&tab=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_STATS_TAB); ?>" class="nav-tab <?php if ($tab === \SnapWizard\Helpers\Constants::SNAPWIZARD_STATS_TAB): ?>nav-tab-active<?php endif; ?>">
        <?php esc_html_e(ucfirst(\SnapWizard\Helpers\Constants::SNAPWIZARD_STATS_TAB)); ?>
    </a>
    <a href="?page=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_MENU_SLUG); ?>&tab=<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_DOCUMENTATION_TAB); ?>" class="nav-tab <?php if ($tab === \SnapWizard\Helpers\Constants::SNAPWIZARD_DOCUMENTATION_TAB): ?>nav-tab-active<?php endif; ?>">
        <?php esc_html_e(ucfirst(\SnapWizard\Helpers\Constants::SNAPWIZARD_DOCUMENTATION_TAB)); ?>
    </a>
<?php endif; ?>