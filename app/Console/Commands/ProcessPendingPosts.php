<?php

namespace App\Console\Commands;

use App\Enums\AiLog\Status;
use App\Enums\User\GarbagePostImage\Type;
use App\Repositories\GarbagePostRepository;
use App\Repositories\AiPostQueueRepository;
use App\Repositories\AiLogRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class ProcessPendingPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:process-pending-posts {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending posts';

    protected $garbagePostRepository;
    protected $aiPostQueueRepository;
    protected $aiLogRepository;

    public function __construct(
        GarbagePostRepository $garbagePostRepository,
        AiPostQueueRepository $aiPostQueueRepository,
        AiLogRepository $aiLogRepository
    ) {
        parent::__construct();
        $this->garbagePostRepository = $garbagePostRepository;
        $this->aiPostQueueRepository = $aiPostQueueRepository;
        $this->aiLogRepository = $aiLogRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $postsForProcess = $this->aiPostQueueRepository->queryWithPost()
                ->get()->pluck('post');

            foreach ($postsForProcess as $post) {

                if (!$post->images) {
                    continue;
                }
                // This url is just an example
                $aiServiceUrl = 'https://ai-service-url.com'; 

                $client = new Client();
                
                $postData = [
                    'post_id' => $post->id,
                    'befor_images' => $post->images->where('type', Type::BEFORE),
                    'after_images' => $post->images->where('type', Type::AFTER),
                ];

                $response = $client->post($aiServiceUrl, [
                    'json' => $postData,
                ]);

                if ($response->getStatusCode() === 200) {
                    $aiResult = json_decode($response->getBody()->getContents(), true);
                    // $aiResult = json_decode('{"status":"approved"}', true);

                    // handle post
                    $post->ai_verification_status = $aiResult['status'];
                    $post->ai_verification_date = now();
                    $post->save();
                    // save log
                    $this->aiLogRepository->create([
                        'garbage_post_id' => $post->id,
                        'verification_status' => $aiResult['status'],
                        'status' => Status::SUCCESS
                    ]);
                    // remove from queue
                    $this->aiPostQueueRepository->queryByPostId($post->id)->delete();
                } else {
                    // save log
                    $this->aiLogRepository->create([
                        'garbage_post_id' => $post->id,
                        'status' => Status::FAILURE
                    ]);
                }
            }

            if ($this->option('dry-run')) {
                DB::rollBack();
            } else {
                DB::commit();
            }

            $this->info('Pending posts processed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!');
        }
        
    }
}
