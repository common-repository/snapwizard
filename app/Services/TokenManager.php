<?php

namespace SnapWizard\Services;

use WP_REST_Request;
use Lablnet\Encryption;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplayException;
use SnapWizard\Helpers\Constants;

class TokenManager
{
    /**
     * @param WP_REST_Request $request
     * @return void
     * @throws InstagramBasicDisplayException
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function snapwizard_create_token_from_oauth_redirect_url_public_page(WP_REST_Request $request): void
    {
        /*
        // You can get the combined, merged set of parameters:
        $parameters = $request->get_params();

        // The individual sets of parameters are also available, if needed:
        $parameters = $request->get_url_params();
        $parameters = $request->get_query_params();
        $parameters = $request->get_body_params();
        $parameters = $request->get_json_params();
        $parameters = $request->get_default_params();
        */

        $snapWizardSecretKey = get_option('snapwizard_secret_key');
        $snapWizardAppId = get_option('snapwizard_app_id');
        $snapWizardAppSecret = get_option('snapwizard_app_secret');
        $snapWizardRedirectUrl = get_option('snapwizard_redirect_url');

        $instagram = new InstagramBasicDisplay([
            'appId'         => $snapWizardAppId,
            'appSecret'     => $snapWizardAppSecret,
            'redirectUri'   => $snapWizardRedirectUrl
        ]);

        if ($request->get_route() ===
            '/' . Constants::SNAPWIZARD_API_NAMESPACE . Constants::SNAPWIZARD_API_AUTH) {

            // Get the OAuth callback code
            // isset: key exists and value is not null
            if (isset($request->get_query_params()['code'])) {

                $code = trim(htmlspecialchars($request->get_query_params()['code']));

                if ($code) {
                    // Get the short-lived access token (valid for 1 hour)
                    $token = $instagram->getOAuthToken($code, true);

                    // Exchange this token for a long lived token (valid for 60 days)
                    $longLivedToken = $instagram->getLongLivedToken($token, true);

                    // Encrypt the token
                    $encryption = new Encryption($snapWizardSecretKey);
                    $cryptedToken = $encryption->encrypt($longLivedToken);

                    // Save on DB for later
                    add_option('snapwizard_crypted_token', $cryptedToken);
                    add_option('snapwizard_last_refreshing_token', date("Y-m-d H:i:s"));
                }
            }
        }

        header("Location: " . site_url() . '/wp-admin/admin.php?page=' . Constants::SNAPWIZARD_MENU_SLUG);
        exit;
    }

    /**
     * @param InstagramBasicDisplay $instagram
     * @param string $decryptedToken
     * @param string $snapWizardSecretKey
     * @throws InstagramBasicDisplayException
     */
    public function refreshingToken(InstagramBasicDisplay $instagram, string $decryptedToken, string $snapWizardSecretKey): void
    {
        $refreshedToken = $instagram->refreshToken($decryptedToken, true);

        // Encrypt the token
        $encryption = new Encryption($snapWizardSecretKey);
        $cryptedToken = $encryption->encrypt($refreshedToken);

        // Update on DB for later
        update_option('snapwizard_crypted_token', $cryptedToken);
        update_option('snapwizard_last_refreshing_token', date("Y-m-d H:i:s"));
    }
}
