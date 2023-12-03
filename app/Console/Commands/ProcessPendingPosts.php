<?php

namespace App\Console\Commands;

use App\Enums\User\GarbagePostImage\Type;
use App\Repositories\GarbagePostRepository;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class ProcessPendingPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:process-pending-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending posts';

    protected $garbagePostRepository;

    public function __construct(GarbagePostRepository $garbagePostRepository)
    {
        parent::__construct();
        $this->garbagePostRepository = $garbagePostRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $pendingPosts = $this->garbagePostRepository
            ->queryPendingPost()
            ->with('images')
            ->get();

            foreach ($pendingPosts as $post) {

                // This url is just an example
                $aiServiceUrl = 'https://ai-service-url.com'; 

                $client = new Client();
                
                $postData = [
                    'post_id' => $post->id,
                    'befor_images' => $post->imanges->where('type', Type::BEFORE),
                    'after_images' => $post->imanges->where('type', Type::AFTER),
                ];

                $response = $client->post($aiServiceUrl, [
                    'json' => $postData,
                ]);

                $aiResult = $response->getBody()->getContents();

                $post->ai_verification_status = $aiResult;
                $post->ai_verification_date = now();
                $post->save();
            }

            $this->info('Pending posts processed successfully!');
        } catch (\Exception $e) {
            $this->info('Something went wrong!');
        }
        
    }
}
