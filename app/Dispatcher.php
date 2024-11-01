<?php

namespace SnapWizard;

use SnapWizard\Helpers\Constants;
use SnapWizard\Helpers\Field;
use SnapWizard\Helpers\AdminSettings;
use SnapWizard\Helpers\Logger as SnapWizardLogger;
use SnapWizard\Services\TokenManager;
use SnapWizard\Services\FeedProcessorService;
use SnapWizard\Services\WordPressBridgeService;
use SnapWizard\Filters\Admin\Filters as AdminFilters;
use SnapWizard\Actions\Admin\Actions as AdminActions;
use SnapWizard\Actions\Public\Actions as PublicActions;
use SnapWizard\Actions\Ajax\Actions as AjaxActions;
use SnapWizard\Interfaces\iDispatcher;

class Dispatcher implements iDispatcher
{
    private static ?Dispatcher $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): iDispatcher
    {
        if (self::$instance === null) {
            self::$instance = new Dispatcher();
        }

        return self::$instance;
    }

    /**
     * @return void
     */
    public function dispatch(): void
    {
        if (is_admin() && (! defined('DOING_AJAX') || ! DOING_AJAX)) {
            new AdminFilters();
            new AdminActions(new Field(), new AdminSettings());

            return;
        }

        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {
            new AjaxActions();

            return;
        }

        $logger = new SnapWizardLogger();
        $logger->setAction(Constants::SNAPWIZARD_SIDE_PUBLIC);
        $logInstance = $logger->getInstance();

        $wordPressBridge = new WordPressBridgeService($logInstance);

        $tokenManager = new TokenManager();
        $feedProcessorService = new FeedProcessorService($tokenManager, $wordPressBridge, $logInstance);

        new PublicActions($tokenManager, $feedProcessorService);
    }
}
