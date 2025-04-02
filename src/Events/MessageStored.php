<?php

namespace PodPoint\MailExport\Events;

use Illuminate\Foundation\Events\Dispatchable;
use PodPoint\MailExport\StorageOptions;
use Symfony\Component\Mime\Email;

class MessageStored
{
    use Dispatchable;

    /**
     * The message instance.
     */
    public Email $message;

    /**
     * The filesystem storage options used to store the message including
     * the disk, the path and the filename with its extension.
     */
    public StorageOptions $storageOptions;

    /**
     * Create a new event instance.
     */
    public function __construct(Email $message, StorageOptions $storageOptions)
    {
        $this->message = $message;
        $this->storageOptions = $storageOptions;
    }
}
