<?php

namespace SnapWizard\Helpers;

use SnapWizard\Interfaces\iField;

class Field implements iField
{
    /**
     * @param string $id
     * @param string $label
     * @param array $callback
     * @param string $page
     * @param string $section
     * @return void
     */
    public function addSettingsField(string $id, string $label, array $callback, string $page, string $section): void
    {
        add_settings_field(
            $id,
            sprintf(
                esc_html__('%s'),
                esc_html($label)
            ),
            $callback,
            $page,
            $section,
            []
        );
    }

    /**
     * @param string $optionGroup
     * @param string $optionName
     */
    public function registerSetting(string $optionGroup, string $optionName): void
    {
        register_setting(
            $optionGroup,
            $optionName,
        );
    }
}
