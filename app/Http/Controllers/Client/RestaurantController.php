<?php

namespace App\Http\Controllers\Client;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; 

class RestaurantController extends Controller
{
    public function AllMenu()
    {
        $menu = Menu::latest()->get();
        return view('client.backend.menu.all_menu', compact('menu'));
    }
}
