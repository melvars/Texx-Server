<?php

namespace Api\Posts\Console;

use Api\Posts\Repositories\PostRepository;
use Illuminate\Console\Command;

class AddPostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:add {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a new post';

    /**
     * Post repository to persist post in database
     *
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * Create a new command instance.
     *
     * @param  PostRepository  $postRepository
     * @return void
     */
    public function __construct(PostRepository $postRepository)
    {
        parent::__construct();

        $this->postRepository = $postRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $post = $this->postRepository->create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => $this->argument('password')
        ]);

        $this->info(sprintf('A post was created with ID %s', $post->id));
    }
}