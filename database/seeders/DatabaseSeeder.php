<?php

namespace Database\Seeders;

use App\Constants\SettingName;
use App\Constants\UserRole;
use App\Models\Category;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createAdminUser();
        $this->seedSettings();
        $this->seedPages();
        $this->seedCategories();
    }

    private function createAdminUser(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@shop.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
            ]
        );
    }

    private function seedSettings(): void
    {
        $settings = [
            SettingName::SITE_NAME->value => json_encode(['en' => 'Rabby TCG'], JSON_UNESCAPED_UNICODE),
            SettingName::SITE_TAGLINE->value => json_encode(['en' => 'PREMIUM TCG CARD SHOP • AUTHENTIC CARDS • WORLDWIDE SHIPPING'], JSON_UNESCAPED_UNICODE),
            SettingName::SITE_DESCRIPTION->value => json_encode(['en' => 'Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.'], JSON_UNESCAPED_UNICODE),
            SettingName::FOOTER_ABOUT->value => json_encode(['en' => 'Rabby TCG connects collectors with authentic trading cards, secure checkout, and responsive collector support.'], JSON_UNESCAPED_UNICODE),
            SettingName::FOOTER_COPYRIGHT->value => json_encode(['en' => 'All rights reserved. Designed by Truonghocdot.'], JSON_UNESCAPED_UNICODE),
            SettingName::SUPPORT_HOURS->value => json_encode(['en' => '08:00AM - 10:00PM'], JSON_UNESCAPED_UNICODE),
            SettingName::SITE_CONTACT_EMAIL->value => 'support@rabbytcg.com',
            SettingName::SITE_CONTACT_PHONE->value => '0986.526.036',
            SettingName::BIN_BANK->value => '970422',
            SettingName::ACCOUNT_NUMBER->value => '0986526036',
            SettingName::ACCOUNT_NAME->value => 'LE VIET ANH',
            SettingName::PHONE_NUMBER->value => '0986526036',
            SettingName::ZALO_LINK->value => 'https://zalo.me/0986526036',
            SettingName::FACEBOOK_LINK->value => 'https://www.facebook.com/le.vietanh.939173',
            SettingName::INSTAGRAM_LINK->value => 'https://www.instagram.com/rabbytcg',
            SettingName::TIKTOK_LINK->value => 'https://www.tiktok.com/@lee.vanhh',
            SettingName::BANKING->value => 'VP Bank',
            SettingName::POPUP_CONTENT->value => json_encode(['en' => '<p>Welcome to Rabby TCG.</p>'], JSON_UNESCAPED_UNICODE),
            SettingName::PAYPAL_ENABLED->value => '1',
            SettingName::PAYPAL_CLIENT_ID->value => 'sb',
            SettingName::PAYPAL_CURRENCY->value => 'USD',
            SettingName::PAYPAL_ENVIRONMENT->value => 'sandbox',
        ];

        foreach ($settings as $name => $value) {
            Setting::updateOrCreate(
                ['setting_name' => $name],
                ['setting_value' => $value]
            );
        }
    }

    private function seedPages(): void
    {
        $pages = [
            [
                'slug' => Page::SLUG_ABOUT_US,
                'title' => ['en' => 'About Us'],
                'meta_title' => ['en' => 'About Us - Rabby TCG'],
                'meta_description' => ['en' => 'Learn more about Rabby TCG, our mission, and our commitment to authentic trading cards.'],
                'content' => ['en' => '<p>Rabby TCG is built for collectors who value authenticity, transparent pricing, and dependable service.</p><p>We curate premium singles, graded cards, and sealed products for an international audience.</p>'],
                'show_in_header' => true,
                'show_in_footer' => true,
                'sort_order' => 10,
            ],
            [
                'slug' => Page::SLUG_CONTACT,
                'title' => ['en' => 'Contact'],
                'meta_title' => ['en' => 'Contact - Rabby TCG'],
                'meta_description' => ['en' => 'Contact Rabby TCG for order support, card sourcing, and collector inquiries.'],
                'content' => ['en' => '<p>Email: support@rabbytcg.com</p><p>Phone: 0986.526.036</p><p>Facebook, Instagram, and Zalo support links are available in the footer.</p>'],
                'show_in_header' => true,
                'show_in_footer' => true,
                'sort_order' => 20,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'meta_title' => $page['meta_title'],
                    'meta_description' => $page['meta_description'],
                    'content' => $page['content'],
                    'status' => Page::STATUS_ACTIVE,
                    'show_in_header' => $page['show_in_header'],
                    'show_in_footer' => $page['show_in_footer'],
                    'sort_order' => $page['sort_order'],
                ]
            );
        }
    }

    private function seedCategories(): void
    {
        $categories = [
            [
                'title' => 'Yu-Gi-Oh!',
                'slug' => 'yu-gi-oh',
                'description' => "Unleash your inner duelist with the Yu-Gi-Oh! Trading Card Game sealed products. From booster boxes packed with powerful monsters to structure decks designed for instant play, every sealed pack holds the potential to summon greatness - just like in the classic manga and anime that started it all.",
                'image' => 'categories/unJkge2eUvT79QxfIqsJ.webp',
                'meta_title' => 'Yu-Gi-Oh!',
                'meta_description' => 'Yu-Gi-Oh!',
            ],
            [
                'title' => 'Pokemon',
                'slug' => 'pokemon',
                'description' => "Step into the world of Pokemon - where every pack holds the thrill of discovery, and every card tells its own story. From the first adventures in Kanto to today's ever-evolving generations, this collection celebrates everything that makes the Pokemon TCG so iconic.",
                'image' => 'categories/1sku5OCoIdo9k2DTH1Bs.webp',
                'meta_title' => 'Pokemon',
                'meta_description' => 'Pokemon',
            ],
            [
                'title' => 'Naruto',
                'slug' => 'naruto',
                'description' => 'In Naruto Mythos Trading Card Game, you and your opponent will compete as the Kage of your respective hidden ninja village to see who leads the strongest faction. Delegate which of your ninja soldiers you will send out to complete missions, focusing on a strategy that will earn you the points to win the game before your opponent does.',
                'image' => 'categories/zL4ANXUYfB3Gxe7rOdHM.webp',
                'meta_title' => 'Naruto',
                'meta_description' => 'Naruto',
            ],
            [
                'title' => 'One Piece',
                'slug' => 'one-piece',
                'description' => "Embark on a manga-inspired adventure with the One Piece Trading Card Game sealed products - where every pack holds the thrill of the Grand Line. Whether you're chasing iconic Straw Hat showdowns, hunting for rare parallels, or simply building your ultimate pirate crew, this is your port of call. From booster boxes bursting with adventure to starter decks ready to battle straight out of the box, our sealed One Piece range offers something for every collector and competitor.\n\nPerfect for collectors, traders, or tournament players, these sealed treasures keep your pulls fresh and your excitement untamed. Ready to hoist your flag and start your next card hunt? Stock your deck, seal your loot, and join the crew at The Card Vault's One Piece collection.",
                'image' => 'categories/WGZLUlZjkSdQhVSnwtbA.webp',
                'meta_title' => 'One Piece',
                'meta_description' => 'One Piece',
            ],
            [
                'title' => 'Riftbound: League of Legends',
                'slug' => 'riftbound-league-of-legends',
                'description' => "Riftbound: League of Legends Trading Card Game brings the legendary champions, iconic spells, and epic moments of Runeterra into an exciting, competitive TCG format. Whether you're a die-hard LoL fan or a seasoned card game player, Riftbound delivers fast-paced, strategic gameplay, stunning artwork, and collectible value all in one.",
                'image' => 'categories/0ncGooVi6exgYxxCYjD6.webp',
                'meta_title' => 'Riftbound: League of Legends',
                'meta_description' => 'Riftbound: League of Legends',
            ],
            [
                'title' => 'Weiss Schwarz',
                'slug' => 'weiss-schwarz',
                'description' => "Enter the vibrant world of Weiss Schwarz, where anime, manga, and gaming collide in one dynamic trading card game. The Card Vault's Weiss Schwarz collection celebrates your favourite series, bringing beloved characters and unforgettable moments to life through beautifully designed cards and exciting gameplay.",
                'image' => 'categories/RVbJBhtxP6JSAFuAvgtX.webp',
                'meta_title' => 'Weiss Schwarz',
                'meta_description' => 'Weiss Schwarz',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'parent_id' => null,
                    'title' => $category['title'],
                    'description' => $category['description'],
                    'image' => $category['image'],
                    'meta_title' => $category['meta_title'],
                    'meta_description' => $category['meta_description'],
                ]
            );
        }
    }
}
