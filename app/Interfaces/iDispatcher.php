<?php

namespace SnapWizard\Interfaces;

interface iDispatcher
{
    public static function getInstance(): iDispatcher;
    public function dispatch(): void;
}
