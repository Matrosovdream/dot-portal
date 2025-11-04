<?php

namespace App\Services\User;

use App\Models\UserQueryBalance;
use App\Repositories\User\UserQueryBalanceHistoryRepo;

class UserQueryBalanceService {

    protected $userQueryBalance;
    protected $queryBalanceHistoryRepo;

    public function __construct(

    )
    { 
        $this->userQueryBalance = new UserQueryBalance();
        $this->queryBalanceHistoryRepo = new UserQueryBalanceHistoryRepo();
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

    public function addBalanceUser( $user_id, $type, $quantity, array $options = [] )
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
            $balanceRecord = $this->userQueryBalance->create( [
                'user_id' => $user_id,
                'type' => $type,
                'amount' => $quantity,
            ] );
        }

        // Log the balance addition in history
        $this->queryBalanceHistoryRepo->create( [
            'user_id' => $user_id,
            'user_company_id' => 0,
            'amount' => $quantity,
            'type' => 'add',
            'initiator' => $options['initiator'] ?? null,
            'initiator_id' => $options['initiator_id'] ?? null,
        ] );


    }

    public function deductBalanceUser( $user_id, $type, $quantity, array $options = [] )
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

        // Log the balance addition in history
        $this->queryBalanceHistoryRepo->create( [
            'user_id' => $user_id,
            'user_company_id' => 0,
            'amount' => $quantity,
            'type' => 'deduct',
            'initiator' => $options['initiator'] ?? null,
            'initiator_id' => $options['initiator_id'] ?? null,
        ] );

    }

}