<?php

namespace PodPoint\MailExport\Concerns;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Contracts\ShouldExport;
use PodPoint\MailExport\StorageOptions;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

/**
 * @mixin Mailable
 */
trait Exportable
{
    /**
     * {@inheritDoc}
     */
    public function send($mailer)
    {
        $this->withSymfonyMessage(function (Email $message) {
            if (! $this instanceof ShouldExport) {
                return;
            }

            $storageOptions = (new StorageOptions($message, [
                'disk' => $this->exportOption('exportDisk'),
                'path' => $this->exportOption('exportPath'),
                'filename' => $this->exportOption('exportFilename'),
            ]))->toJson();

            $message->getHeaders()->add(new UnstructuredHeader('X-Storage-Options', $storageOptions));
        });

        parent::send($mailer);
    }

    /**
     * Tries to resolve storage options from an optional method and property.
     */
    private function exportOption(string $key): ?string
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        }

        return property_exists($this, $key) ? $this->$key : null;
    }
}
