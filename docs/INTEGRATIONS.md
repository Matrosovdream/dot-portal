# Third-party API integrations

This portal talks to external APIs through one consistent layered pattern. This
document lists every integration, its credentials, and how to call it.

## The pattern

```
Client  (app/Mixins/Integrations/<Name>API.php)   raw HTTP transport, one per provider
   ▲ extends
AbstractIntegrationApi                            shared request/error/auth plumbing
   │
Service (app/Services/<Domain>/<Name>Service.php)  single app-facing entry point
   │   - constructor-injects the client
   │   - validates input, maps the raw response to clean snake_case domain arrays
   ▼
Actions / Jobs / Controllers                       call the Service, never the client
```

### `AbstractIntegrationApi`

`app/Mixins/Integrations/AbstractIntegrationApi.php` is the base every client
extends. It gives each client:

- `get() / post() / put() / delete()` helpers over Laravel's `Http` client.
- A normalised return shape: the decoded JSON `array` on success, or an error
  array `['error' => true, 'status' => int, 'message' => string]` on any
  failure (HTTP error **or** thrown exception — clients never throw).
- `isConfigured()` — when required credentials are missing the call short-circuits
  to a clean `503` error array instead of hitting the network.
- `AbstractIntegrationApi::failed($result)` — static helper to detect an error
  array. Services use it to pass errors straight through.

Subclasses set `$apiTitle` / `$baseUrl`, override `client()` to attach auth
(bearer / basic / query / body), and override `isConfigured()`.

### Checking results

```php
use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Services\Vehicle\VinDecodeService;

$result = app(VinDecodeService::class)->decode('1FUJGLDR0CLBP8834');

if (AbstractIntegrationApi::failed($result)) {
    // $result['status'], $result['message']
}
```

## Integrations

Status legend — **live**: real public/credentialed API, ready to call ·
**scaffold**: real endpoint shape wired up, needs a provider account / contract
(and, where noted, regulated consent) before production use. Every scaffold
still degrades to a clean error array when unconfigured — nothing crashes.

| Integration | Status | Client | Service | Config (`services.*`) | Domain |
|---|---|---|---|---|---|
| NHTSA vPIC (VIN decode) | live · keyless | `NhtsaVpicAPI` | `Vehicle/VinDecodeService` | — | Vehicle |
| NHTSA Safety (recalls/complaints) | live · keyless | `NhtsaSafetyAPI` | `Vehicle/RecallService` | — | Vehicle |
| FMCSA QCMobile (carrier) | live | `FmcsaQcMobileAPI` | `Fmcsa/CarrierService` | `fmcsa.web_key` | UserCompany / CompanySaferweb |
| Twilio SMS | live | `TwilioApi` | `Notification/SmsService` | `twilio.sid` `twilio.token` `twilio.from` | Notification / reminders |
| Stripe | live | `StripeApi` | `Payments/StripePaymentService` | `stripe.secret` | Payments |
| Plaid | live | `PlaidApi` | `Payments/BankVerificationService` | `plaid.client_id` `plaid.secret` `plaid.env` | Payments / ACH |
| Smarty (address) | live | `SmartyApi` | `Address/AddressValidationService` | `smarty.auth_id` `smarty.auth_token` | Addresses |
| MVR aggregator | scaffold | `MvrApi` | `Driver/MvrService` | `mvr.base_url` `mvr.api_key` `mvr.provider` | DriverMvr |
| FMCSA Clearinghouse | scaffold | `ClearinghouseApi` | `Driver/ClearinghouseService` | `clearinghouse.base_url` `clearinghouse.api_key` | DriverDrugTest / Clearinghouse module |
| FMCSA PSP | scaffold | `PspApi` | `Driver/PspService` | `psp.base_url` `psp.account_id` `psp.api_key` | Driver hiring |
| DocuSign | scaffold | `DocusignApi` | `Document/ESignatureService` | `docusign.base_uri` `docusign.account_id` `docusign.access_token` | DriverDocument / File |
| Document OCR | scaffold | `DocumentOcrApi` | `Document/DocumentOcrService` | `ocr.base_url` `ocr.api_key` `ocr.provider` | DriverCdlLicense / DriverMedicalCard |
| Samsara ELD | scaffold (needs token) | `SamsaraApi` | `Fleet/TelematicsService` | `samsara.api_token` `samsara.base_url` | Vehicle / VehicleInspection |

All credentials map to `.env` keys documented in `.env.example`. NHTSA needs
none. The pre-existing `SaferwebAPI` / `TranspGovAPI` remain unchanged.

## Service entry points

```php
// VIN decode → normalised vehicle attributes (vehicles table only stores `vin` today)
app(VinDecodeService::class)->decode(string $vin, ?int $year = null): array;

// Open recalls / complaints for a vehicle
app(RecallService::class)->forVehicle(string $make, string $model, int $year): array;
app(RecallService::class)->complaints(string $make, string $model, int $year): array;

// Official FMCSA carrier snapshot + BASIC scores by USDOT
app(CarrierService::class)->snapshot(string $usdot): array;
app(CarrierService::class)->basics(string $usdot): array;

// SMS — call from a scheduled reminder job over expiring CDL / medical card / drug test
app(SmsService::class)->send(string $to, string $body): array;

// Stripe (standalone gateway — does NOT replace AuthnetService / PaymentInterface)
app(StripePaymentService::class)->createCustomer(string $email, ?string $name = null): array;
app(StripePaymentService::class)->charge(string $customerId, int $amountCents, string $currency = 'usd', ?string $description = null): array;
app(StripePaymentService::class)->createSubscription(string $customerId, string $priceId): array;

// Plaid bank verification / ACH
app(BankVerificationService::class)->createLinkToken(string $userId, string $clientName = 'DotPortal'): array;
app(BankVerificationService::class)->exchange(string $publicToken): array;
app(BankVerificationService::class)->account(string $accessToken): array;

// US address validation
app(AddressValidationService::class)->validate(array $address): array; // ['street','city','state','zipcode']

// Driver compliance (scaffold — capture & record consent BEFORE calling)
app(MvrService::class)->order(array $driver): array;            app(MvrService::class)->fetch(string $orderId): array;
app(ClearinghouseService::class)->query(array $driver, string $type = 'limited'): array;
app(ClearinghouseService::class)->result(string $queryId): array;
app(PspService::class)->order(array $driver): array;           app(PspService::class)->fetch(string $reportId): array;

// Documents (scaffold)
app(ESignatureService::class)->sendForSignature(string $signerEmail, string $signerName, string $subject, string $documentBase64, string $documentName = 'document.pdf'): array;
app(DocumentOcrService::class)->extractCdl(string $documentUrl): array;
app(DocumentOcrService::class)->extractMedicalCard(string $documentUrl): array;

// Telematics (Samsara)
app(TelematicsService::class)->vehicles(): array;
app(TelematicsService::class)->locations(): array;
app(TelematicsService::class)->hoursOfService(string $start, string $end): array;
app(TelematicsService::class)->inspectionReports(string $start, string $end): array;
```

## Compliance notes (scaffolds)

These integrations move regulated data. The scaffold code always sends
`consent: true`, but **the calling layer is responsible for capturing and
recording the driver's consent first**:

- **MVR** — DPPA + FCRA; driver consent required before pulling records.
- **FMCSA Clearinghouse** — no public FMCSA REST API; access is brokered through
  a C/TPA partner. A *limited* query needs general consent, a *full* query needs
  specific electronic consent.
- **PSP** — paid per report, FCRA consumer report; applicant consent required.
- **Twilio** — TCPA: honour opt-in and STOP opt-out for SMS.
- **Stripe** — PCI-DSS: never send raw card PANs; use tokenised payment methods.
- **Plaid** — GLBA / ACH-NACHA safeguarding of account & routing numbers.

## Adding another integration

1. Create `app/Mixins/Integrations/<Name>API.php` extending `AbstractIntegrationApi`.
2. Create `app/Services/<Domain>/<Name>Service.php` that constructor-injects the
   client and maps responses to clean domain arrays.
3. Add a `services.<name>` block to `config/services.php` and the matching keys
   to `.env.example`.
4. Resolve the service with `app(<Name>Service::class)` from an Action / Job /
   Controller — never call the client directly.

## Tests

Each integration has a dummy-data test at `tests/Unit/Integrations/<Name>Test.php`
that fakes the provider with `Http::fake()` and asserts the service's mapping,
request shape (URL / verb / auth), the not-configured `503` path, and error
pass-through. They use **no database** (no `RefreshDatabase`) and make no real
network calls.

Run just the integration tests:

```bash
# Inside the app container (PHP 8.4):
php artisan test --testsuite=Unit -- --filter Integrations
# or directly:
vendor/bin/phpunit tests/Unit/Integrations
```

> The vendored dependencies require **PHP ≥ 8.4**. On a host with only PHP 8.3,
> use the 8.4 binary explicitly, e.g.
> `/opt/homebrew/opt/php@8.4/bin/php vendor/bin/phpunit tests/Unit/Integrations`.

Because each client reads its credentials in its constructor, tests
`config()->set('services.*', …)` **before** resolving the service via `app()`.
