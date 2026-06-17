<?php

namespace App\Contracts\Address;

/**
 * US street address verification / standardization.
 *
 * Implementations confirm that a complete address is a real, mailable delivery
 * point and return normalised components aligned to the address tables. This is
 * a confirmation concern — for type-ahead discovery use
 * App\Contracts\Address\AddressSearch instead.
 */
interface AddressVerification
{
    /**
     * Verify and standardize a single complete US street address.
     *
     * @param  array  $address  ['street','city','state','zipcode']
     * @return array  A normalised error array is passed straight through
     *                (['error'=>true,'status'=>int,'message'=>string]); otherwise
     *                ['valid'=>bool,'dpv_match_code'=>?string,
     *                 'normalized'=>['line1','city','state','zipcode','plus4'],
     *                 'raw'=>array].
     */
    public function verify(array $address): array;
}
