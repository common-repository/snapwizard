<?php

namespace SnapWizard\Filters\Admin;

use SnapWizard\Helpers\Constants;

class Filters
{
    public string $side = 'admin';

    public function __construct()
    {
        add_filter(
            'plugin_action_links_' . SNAPWIZARD_SLUG . '/' . SNAPWIZARD_SLUG . '.php',
            [$this, 'snapwizard_settings_link']
        );
    }

    /**
     * @param array<string, string> $links
     * @return array<string, string>
     */
    public function snapwizard_settings_link(array $links): array
    {
        $url = get_admin_url() . "admin.php?page=" . Constants::SNAPWIZARD_MENU_SLUG;
        // target="_blank"

        $settings_link = sprintf('<a href="%s">%s</a>', $url, __('Settings'));

        $links[] = $settings_link;

        return $links;
    }
}
