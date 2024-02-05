<?php

declare(strict_types=1);

namespace RuneLaenen\TwoFactorAuth\Service;

use Shopware\Core\System\SystemConfig\SystemConfigService;

readonly class ConfigurationService
{
    public const string CONFIGURATION_KEY = 'RuneLaenenTwoFactorAuth';

    public function __construct(private SystemConfigService $systemConfig) {}

    public function get(string $key, ?string $salesChannelId = null): mixed
    {
        return $this->systemConfig->get(self::CONFIGURATION_KEY . '.config.' . $key, $salesChannelId);
    }

    public function getAdministrationCompany(?string $salesChannelId = null): string
    {
        return (string) $this->get('administrationCompany', $salesChannelId);
    }

    public function isStorefrontEnabled(?string $salesChannelId = null): bool
    {
        return (bool) $this->get('storefrontEnabled', $salesChannelId);
    }

    public function getStorefrontCompany(?string $salesChannelId = null): string
    {
        return (string) $this->get('storefrontCompany', $salesChannelId);
    }
}
