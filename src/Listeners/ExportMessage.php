<?php

namespace PodPoint\MailExport\Listeners;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Mail\Events\MessageSent;
use PodPoint\MailExport\Events\MessageStored;
use PodPoint\MailExport\StorageOptions;
use Symfony\Component\Mime\Email;

class ExportMessage
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Factory
     */
    protected $filesystem;

    /**
     * Create a new listener instance.
     */
    public function __construct(Factory $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Handles the Event when it happens while listening.
     */
    public function handle(MessageSent $event): void
    {
        if ($this->shouldStoreMessage($event->message)) {
            $this->storeMessage($event->message);
        }
    }

    /**
     * Finds out if whether we should store the mail or not.
     */
    protected function shouldStoreMessage(Email $message): bool
    {
        return $message->getHeaders()->has('X-Storage-Options')
            && config('mail-export.enabled', false);
    }

    /**
     * Actually stores the stringified version of the \Symfony\Component\Mime\Email
     * including headers, recipients, subject and body onto the filesystem disk.
     */
    private function storeMessage(Email $message): void
    {
        $headers = $message->getHeaders();
        if (! $headers->has('X-Storage-Options')) {
            return;
        }
        $storageOptions = json_decode($headers->get('X-Storage-Options')->getBody(), true);
        $storageOptions = new StorageOptions($message, $storageOptions);

        $this->filesystem
            ->disk($storageOptions->disk)
            ->put($storageOptions->fullpath(), $message->toString(), [
                'mimetype' => $storageOptions::MIME_TYPE,
            ]);

        event(new MessageStored($message, $storageOptions));
    }
}
