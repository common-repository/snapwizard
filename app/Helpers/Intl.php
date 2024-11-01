<?php

namespace SnapWizard\Helpers;

class Intl
{
    public static function i_esc_html_e(string $string): void
    {
        printf(
            esc_html__('%s'),
            esc_html($string)
        );
    }

    public static function i_esc_attr_e(string $string): void
    {
        printf(
            esc_html__('%s'),
            esc_attr($string)
        );
    }
}
