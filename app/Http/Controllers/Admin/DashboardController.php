<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\TypeOfService;
use App\Models\TransOrder ;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'customers' => Customer::count(),
            'users' => User::count(),
            'services' => TypeOfService::count(),
            'orders' => TransOrder::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
