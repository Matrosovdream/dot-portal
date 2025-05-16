<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserPaymentCard;
use App\Repositories\User\UserPaymentCardMetaRepo;



class UserPaymentCardRepo extends AbstractRepo
{

    protected $countryStateRepo;
    protected $cardMetaRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new UserPaymentCard();

        $this->cardMetaRepo = new UserPaymentCardMetaRepo();
    }

    public function getPrimaryCard($user_id)
    {
        $raw = $this->model
            ->where('user_id', $user_id)
            ->where('primary', 1)
            ->first();

        return $this->mapItem($raw);
    }

    // Set all cards to not primary
    public function makeUnprimaryByUser( $user_id ) {

        $cards = $this->getAll(['user_id' => $user_id]);

        foreach( $cards['items'] as $card ) {
            $card['Model']->update( ['primary' => 0] );
        }

    }

    public function makeCardPrimary( $card_id ) {

        $card = $this->getByID($card_id);

        if( $card ) {
            // Set all cards to not primary
            $this->makeUnprimaryByUser( $card['user_id'] );

            // Set this card to primary
            $card['Model']->update( ['primary' => 1] );
        }

    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'card_number' => $item->card_number,
            'cardholder_name' => $item->card_holder_name,
            'expiry_date' => $item->expiry_date,
            'payment_method_id' => $item->payment_method_id,
            'primary' => $item->primary,
            'Meta' => $this->cardMetaRepo->mapItems($item->meta),
            'Model' => $item
        ];

        return $res;
    }

}