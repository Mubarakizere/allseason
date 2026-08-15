<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Tasty African Delights',
                'subtitle' => null,
                'description' => 'Experience the vibrant flavors of Africa with dishes crafted to perfection. Each bite takes you closer to tradition and joy.',
                'image' => '/assets/images/banner5.jpg',
                'btn_text_1' => 'Order Online',
                'btn_link_1' => route('menu'),
                'btn_text_2' => null,
                'btn_link_2' => null,
                'overlay_class' => 'overlay_bg_40',
                'align' => 'left',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Wedding & Events',
                'subtitle' => 'Celebrate With Us',
                'description' => 'Host your dream wedding and memorable events in our exquisite garden venues. Perfect settings tailored for your special celebrations.',
                'image' => '/assets/images/banner1.jpg',
                'btn_text_1' => 'Explore Venues',
                'btn_link_1' => route('venues.index'),
                'btn_text_2' => 'Book Event',
                'btn_link_2' => route('contact'),
                'overlay_class' => 'overlay_bg_50',
                'align' => 'center',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Accommodation & Rooms',
                'subtitle' => 'Rest & Relax',
                'description' => 'Unwind in our elegant, luxury rooms equipped with modern comforts. Enjoy a tranquil getaway surrounded by serene beauty.',
                'image' => '/assets/images/banner3.jpg',
                'btn_text_1' => 'Book A Room',
                'btn_link_1' => route('rooms.index'),
                'btn_text_2' => 'Explore Rooms',
                'btn_link_2' => route('rooms.index'),
                'overlay_class' => 'overlay_bg_50',
                'align' => 'left',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Choose & Savor',
                'subtitle' => null,
                'description' => 'Indulge in suya and other mouthwatering dishes, infused with authentic spices and crafted to delight your taste buds.',
                'image' => '/assets/images/banner2.jpg',
                'btn_text_1' => 'Order Online',
                'btn_link_1' => route('menu'),
                'btn_text_2' => 'Contact Us',
                'btn_link_2' => route('contact'),
                'overlay_class' => 'overlay_bg_60',
                'align' => 'center',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Enjoy Every Bite',
                'subtitle' => 'Are You Ready',
                'description' => 'From sizzling suya to hearty stews, enjoy African dishes made to bring joy to every occasion and appetite.',
                'image' => '/assets/images/banner6.jpg',
                'btn_text_1' => 'Order Online',
                'btn_link_1' => route('menu'),
                'btn_text_2' => 'Contact Us',
                'btn_link_2' => route('contact'),
                'overlay_class' => 'overlay_bg_40',
                'align' => 'right',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $bannerData) {
            Banner::updateOrCreate(
                ['title' => $bannerData['title']],
                $bannerData
            );
        }
    }
}
