<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Menu;
use App\Models\Room;
use App\Models\Venue;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticUrls = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => date('Y-m-d')],
            ['url' => route('menu'), 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => date('Y-m-d')],
            ['url' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => date('Y-m-d')],
            ['url' => route('contact'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => date('Y-m-d')],
            ['url' => route('blogs'), 'priority' => '0.8', 'changefreq' => 'daily', 'lastmod' => date('Y-m-d')],
            ['url' => route('venues.index'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => date('Y-m-d')],
            ['url' => route('rooms.index'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => date('Y-m-d')],
            ['url' => route('privacy.policy'), 'priority' => '0.3', 'changefreq' => 'yearly', 'lastmod' => date('Y-m-d')],
            ['url' => route('terms.conditions'), 'priority' => '0.3', 'changefreq' => 'yearly', 'lastmod' => date('Y-m-d')],
        ];

        $menus = Menu::all();
        $blogs = Blog::all();
        $venues = Venue::all();
        $rooms = Room::all();

        $content = view('sitemap', compact('staticUrls', 'menus', 'blogs', 'venues', 'rooms'))->render();

        return response($content, 200)->header('Content-Type', 'text/xml');
    }
}
