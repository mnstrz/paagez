<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateNotification extends Command
{
    use TraitModule;

	protected $signature = 'paagez:notification {name?} {--module=}';
	
	protected $description = 'Create model';

	protected $directory = '';

	public function handle()
    {
        if(!$this->option('module') || !$this->argument('name'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create notification \n");
            $this->line("-----------------------------------\n");
            $this->info($this->signature."\n\n");
            $this->line("<fg=yellow>name</>             Notification  name\n");
            $this->line("<fg=yellow>--module</>         Module name\n");
            $this->line("-----------------------------------\n");
            if(!$this->argument('name'))
            {
                $name = $this->ask('Please enter the notification name');
                $this->input->setArgument('name', $name);
            }
            if(!$this->option('module'))
            {
                $module = $this->ask('Please enter the module name');
                $this->input->setOption('module', $module);
            }
        }
        if($this->initials($this->option('module')))
        {
            return 0;
        }
        if($this->strings($this->argument('name'),"/^[A-Za-z0-9_\/\\\\-]+$/"))
        {
            return 0;
        }
        if($this->makeFile())
        {
            return 0;
        }
        return 0;
    }

    public function makeFile()
    {
        $this->line("\nCreating notification ...\n");
        try {

            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/Notifications".$this->module_extra_path)) {
                \File::makeDirectory($this->module_path."/Notifications".$this->module_extra_path, 0755, true);
            }
            $filePath = $this->module_path . '/Notifications'.$this->module_extra_path.'/'.$this->studly_case.'.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '<?php

namespace '.$this->namespace.'\Models'.$this->extra_namespace.';

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class '.$this->studly_case.' extends Model
{
    use HasFactory;

    protected $table = "'.$this->camel_case.'";
}';


            $fileContent = '<?php

namespace '.$this->namespace.'\Notifications'.$this->extra_namespace.';

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class '.$this->studly_case.' extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($url="/",$subject="'.$this->studly_case.'",$message=null)
    {
        $this->url = $url;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Get the notification"s delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ["database","mail"];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject($this->subject)
                    ->line("The introduction to the notification.")
                    ->action("Notification Action", $this->url)
                    ->line($this->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "url" => $this->url,
            "subject" => $this->subject,
            "message" => $this->message
        ];
    }
}';
            \File::put($filePath, $fileContent);
            $this->info("$filePath ........................................... Success\n");
            $this->info('Call this class to using notification <fg=yellow>$user->notify(new \\'.$this->namespace.'\Notifications'.$this->extra_namespace.'\\'.$this->studly_case.'("/","Subject","Message"))</>');
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}