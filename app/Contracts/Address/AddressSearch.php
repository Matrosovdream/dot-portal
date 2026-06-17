<?php

namespace App\Contracts\Address;

/**
 * Address autocomplete / type-ahead search.
 *
 * Implementations turn a partial address string into a short list of
 * suggestion candidates for a UX picker. This is a discovery concern — it does
 * NOT guarantee postal deliverability; pass the chosen address through an
 * App\Contracts\Address\AddressVerification implementation before persisting.
 */
interface AddressSearch
{
    /**
     * Suggest US addresses for a partial input string.
     *
     * @param  string  $text   Partial address typed by the user.
     * @param  int     $limit  Maximum number of suggestions to return.
     * @return array  A normalised error array is passed straight through
     *                (['error'=>true,'status'=>int,'message'=>string]); otherwise
     *                ['suggestions'=>array<int,array>, 'raw'=>array] where each
     *                suggestion exposes: label, line1, city, state (USPS abbr),
     *                zipcode, country, lat, lon, place_id.
     */
    public function suggest(string $text, int $limit = 5): array;
}
