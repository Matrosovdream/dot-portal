<?php
namespace App\Services;

use App\Models\Language;
use App\Models\Currency;
use App\Services\LocationService;
use App\Models\Country;
use App\Services\SiteSettingsService;
use App\Helpers\userSettingsHelper;
use App\Repositories\Cart\CartRepo;


class GlobalsService {

    private $locationService;

    public function __construct() {
        $this->locationService = new LocationService();
    }

    public function getGlobals() {
        return [
            //'geoData' => $this->locationService->getLocation(),
            'languages' => $this->getLanguages(),
            'currencies' => $this->getCurrencies(),
            'countries' => $this->getCountries(),
            'siteSettings' => $this->getSiteSettings()
        ];
    }

    public function getGeoData() {
        return $this->locationService->getLocation();
    }

    public function getLanguages() {
        $list = Language::all()->take(5);

        foreach ($list as $language) {
            $language->active = $language->code == $this->getActiveLanguage()->code;
        }
        return $list;
    }

    public function getCountries() {
        return Country::all();
    }

    public function getCurrencies() {
        $list = Currency::all()->take(5);

        foreach ($list as $currency) {
            $currency->active = $currency->code == $this->getActiveCurrency()->code;
        }
        return $list;
    }

    public function getSiteSettings() {
        return SiteSettingsService::getAllSettings();
    }

    public function getActiveLanguage() {
        return Language::where('code', $this->getActiveLanguageCode() )->first();
    }

    public function getActiveLanguageCode() {
        return $_COOKIE['language'] ?? 'EN';
    }

    public function getActiveCurrency() {
        
        if (isset($_COOKIE['currency'])) {
            $code = $_COOKIE['currency'];
        } else {
            $code = 'USD';
        }
        return Currency::where('code', $code)->first();
    }

    public function getMenuTop() {
        return userSettingsHelper::getTopMenu();
    }

    public static function setCurrency($code) {
        setcookie('currency', $code, time() + 60 * 60 * 24 * 30, '/');
    }

    public static function setLanguage($code) {
        setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/');
    }

    public static function setCart( $cart_id ) {

        $cart = CartRepo::find( $cart_id );

        // Get all carts
        $carts = self::getCarts();

        // Prepare cart data
        $cartData = [
            'id' => $cart['fields']['id'],
            'hash' => $cart['fields']['hash'],    
        ];
        $cartData = json_encode( $cartData );

        // Add or update cart
        $carts[ $cart['fields']['hash'] ] = $cartData;

        // Encode array and set cookie
        $carts = json_encode( $carts );
        setcookie('carts', $carts, time() + 60 * 60 * 24 * 30, '/');

    }

    public static function getCarts() {

        $carts = $_COOKIE['carts'] ?? null;

        if( isset( $carts ) ) {
            return json_decode( $carts, true );
        }
        return [];

    }

}