<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::take(5)->get();
        
        if ($users->count() == 0) {
            return;
        }

        $samplePosts = [
            [
                'title' => 'Welcome to Our Trading Platform',
                'content' => 'Welcome to our comprehensive trading platform! We are excited to provide you with the best trading experience possible. Our platform offers advanced trading tools, real-time market data, secure transactions, and 24/7 customer support.',
                'status' => 'published'
            ],
            [
                'title' => 'Trading Tips for Beginners',
                'content' => 'Are you new to trading? Here are some essential tips to get you started: Start with small investments, learn about risk management, diversify your portfolio, and keep learning and practicing. Remember, successful trading takes time and patience.',
                'status' => 'published'
            ],
            [
                'title' => 'Understanding Market Trends',
                'content' => 'Market trends are crucial for making informed trading decisions. Understanding how to read and interpret market trends can significantly improve your trading success rate. Key factors to consider: Technical analysis, fundamental analysis, market sentiment, and economic indicators.',
                'status' => 'published'
            ],
            [
                'title' => 'Risk Management Strategies',
                'content' => 'Effective risk management is the cornerstone of successful trading. Here are some proven strategies: Set stop-loss orders, never invest more than you can afford to lose, use proper position sizing, and monitor your trades regularly.',
                'status' => 'published'
            ],
            [
                'title' => 'The Future of Digital Trading',
                'content' => 'Digital trading is evolving rapidly with new technologies and innovations. The future looks promising with Artificial Intelligence integration, blockchain technology, mobile trading apps, and automated trading systems.',
                'status' => 'published'
            ]
        ];

        foreach ($samplePosts as $index => $post) {
            $user = $users[$index % $users->count()];
            
            Blog::create([
                'user_id' => $user->id,
                'title' => $post['title'],
                'content' => $post['content'],
                'slug' => Blog::generateSlug($post['title']),
                'status' => $post['status'],
                'likes' => rand(5, 50),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }
} 