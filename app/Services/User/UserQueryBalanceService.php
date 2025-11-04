<?php

namespace App\Services\User;

use App\Models\UserQueryBalance;

class UserQueryBalanceService {

    protected $userQueryBalance;

    public function __construct(

    )
    { 
        $this->userQueryBalance = new UserQueryBalance();
    }

    public function getBalanceUser( $user_id, $type )
    {
        
        // Get user balance record
        $balanceRecord = $this->userQueryBalance
            ->where('user_id', $user_id)
            ->where('type', $type)
            ->first();

        return $balanceRecord ? $balanceRecord->amount : 0;

    }

    public function addBalanceUser( $user_id, $type, $quantity, $order_id = null )
    {
        
        // Check if user balance record exists
        $balanceRecord = $this->userQueryBalance
            ->where('user_id', $user_id)
            ->where('type', $type)
            ->first();

        if( $balanceRecord ) {
            // Update existing balance
            $balanceRecord->amount += $quantity;
            $balanceRecord->save();
        } else {
            // Create new balance record
            $this->userQueryBalance->create( [
                'user_id' => $user_id,
                'type' => $type,
                'amount' => $quantity,
            ] );
        }


    }

    public function deductBalanceUser( $user_id, $type, $quantity )
    {
        
        // Get user balance record
        $balanceRecord = $this->userQueryBalance
            ->where('user_id', $user_id)
            ->where('type', $type)
            ->first();

        if( $balanceRecord && $balanceRecord->amount >= $quantity ) {
            // Deduct balance
            $balanceRecord->amount -= $quantity;
            $balanceRecord->save();
            return true;
        }

    }

}