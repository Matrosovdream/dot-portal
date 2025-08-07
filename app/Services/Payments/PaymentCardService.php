<?php

namespace App\Services\Payments;

use App\Repositories\User\UserPaymentCardRepo;
use App\Actions\Dashboard\SubscriptionUserActions;

class PaymentCardService
{
    
    public function storeCard(array $data, $user_id=null): ?array
    {
        
        $subActions = app(SubscriptionUserActions::class);

        return $subActions->storeCard($data);

    }

    public function getUserPrimaryCard( $user_id ): ?array
    {
        
        $userCardRepo = app(UserPaymentCardRepo::class);

        $primaryCard = $userCardRepo->getUserPrimaryCard($user_id);

        if( $primaryCard ) {
            return [
                'id' => $primaryCard['id'],
                'customerProfileId' => $primaryCard['Meta']['authnet_profile_id'],
                'paymentProfileId' => $primaryCard['Meta']['authnet_payment_profile_id'],
            ];
        } else {
            return null;
        }
        

    }

}

