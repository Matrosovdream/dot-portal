<?php

namespace App\Services\DummyData;

/**
 * Central configuration + marker convention for generated dummy data.
 *
 * Every dummy record is reachable from a dummy *user* (a company account or a
 * driver login). Dummy users are identified purely by their email domain, so a
 * single LIKE pattern lets us find and wipe everything we created without
 * touching real data. See DummyDataGeneratorService::wipe().
 */
class DummyConfig
{
    /** Email domain that marks a user (and therefore all its data) as dummy. */
    public const EMAIL_DOMAIN = 'dummy.dotportal.test';

    /** Role ids (see RoleSeeder): 1 admin, 2 manager, 3 company, 4 driver. */
    public const ROLE_COMPANY = 3;
    public const ROLE_DRIVER  = 4;

    /** Shared login password for every generated account. */
    public const PASSWORD = 'password';

    /**
     * Default volumes. Ranges are [min, max] and resolved per-company with Faker.
     * The company count is overridable from the command (--companies=).
     */
    public const COMPANIES                = 8;
    public const DRIVERS_PER_COMPANY      = [2, 6];
    public const VEHICLES_PER_COMPANY     = [1, 4];
    public const CARDS_PER_COMPANY        = [1, 2];
    public const REQUESTS_PER_COMPANY     = [2, 5];
    public const ORDERS_PER_COMPANY       = [1, 4];
    public const TASKS_PER_COMPANY        = [1, 3];
    public const NOTIFICATIONS_PER_COMPANY = [2, 4];

    /** How many months of subscription billing history to back-fill. */
    public const BILLING_HISTORY_MONTHS = 6;

    public static function companyEmail(int $index): string
    {
        return "dummy+co-{$index}@" . self::EMAIL_DOMAIN;
    }

    public static function driverEmail(int $companyUserId, int $n): string
    {
        return "dummy+drv-{$companyUserId}-{$n}@" . self::EMAIL_DOMAIN;
    }

    public static function isDummyEmail(?string $email): bool
    {
        return $email !== null && str_ends_with($email, '@' . self::EMAIL_DOMAIN);
    }

    /** SQL LIKE pattern matching every dummy user email. */
    public static function emailLikePattern(): string
    {
        return '%@' . self::EMAIL_DOMAIN;
    }
}
