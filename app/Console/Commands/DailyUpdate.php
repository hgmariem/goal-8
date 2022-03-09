<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Mail;
use App\Model\TaskTemplate;

class DailyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Email has been sent successfully.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $task_template = new TaskTemplate();
        
        $task_template->process_task();

        Log::info('Running cron: '.date("Y-m-d H:i:s"));

		Mail::raw("This is automatically generated Hourly Update", function($message)
		{
		 
			   $message->from('info@keyhabits.com');
		 
			   $message->to("livehemantg@gmail.com")->subject('Daily Update');
		 
		});
	  $this->info('Hourly Update has been send successfully');
    }
}
