<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;


class DashboardGatewaysController extends Controller
{
    
    public function index()
    {
        $data = [
            'title' => 'Payment Gateways',
            'gateways' => PaymentGateway::paginate(10)
        ];
        return view('dashboard.gateways.index', $data);
    }

}
