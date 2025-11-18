<?php
namespace App\Http\Controllers;

use App\Core\Controller;

class PricingController extends Controller
{
    public function index(): void
    {
        $this->view('pricing.index');
    }
}
