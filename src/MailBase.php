<?php

namespace SkyWebDev\DbMail;

use Illuminate\Container\Container;
use Illuminate\Contracts\Mail\Factory as MailFactory;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Collection;

class MailBase extends Mailable
{
    public function __construct()
    {
        $this->setTemplateContent();
    }

    public function setTemplateContent(): void
    {
        /** @var BladeTemplate $template */
        $bladeTemplate = BladeTemplate::query()->where('class_name', get_called_class())->first();
        if (method_exists($this, 'build')) {
            $this->build();
        }
        if (!empty($bladeTemplate->subject)) {
            $this->subject($bladeTemplate->subject);
        }
    }

    public function send($mailer)
    {
        $result = $this->withLocale($this->locale, function () use ($mailer) {
            $this->prepareMailableForDelivery();

            $mailer = $mailer instanceof MailFactory
                ? $mailer->mailer($this->mailer)
                : $mailer;

            return $mailer->send($this->buildView(), $this->buildViewData(), function ($message) {
                $this->buildFrom($message)
                    ->buildRecipients($message)
                    ->buildSubject($message)
                    ->buildTags($message)
                    ->buildMetadata($message)
                    ->runCallbacks($message)
                    ->buildAttachments($message);
            });
        });
        $this->setTemplateContent();
        return $result;
    }

    protected function prepareMailableForDelivery()
    {
//        if (method_exists($this, 'build')) {
//            Container::getInstance()->call([$this, 'build']);
//        }

        $this->ensureHeadersAreHydrated();
        $this->ensureEnvelopeIsHydrated();
        $this->ensureContentIsHydrated();
        $this->ensureAttachmentsAreHydrated();
    }

    private function ensureHeadersAreHydrated()
    {
        if (! method_exists($this, 'headers')) {
            return;
        }

        $headers = $this->headers();

        $this->withSymfonyMessage(function ($message) use ($headers) {
            if ($headers->messageId) {
                $message->getHeaders()->addIdHeader('Message-Id', $headers->messageId);
            }

            if (count($headers->references) > 0) {
                $message->getHeaders()->addTextHeader('References', $headers->referencesString());
            }

            foreach ($headers->text as $key => $value) {
                $message->getHeaders()->addTextHeader($key, $value);
            }
        });
    }

    /**
     * Ensure the mailable's "envelope" data is hydrated from the "envelope" method.
     *
     * @return void
     */
    private function ensureEnvelopeIsHydrated()
    {
        if (! method_exists($this, 'envelope')) {
            return;
        }

        $envelope = $this->envelope();

        if (isset($envelope->from)) {
            $this->from($envelope->from->address, $envelope->from->name);
        }

        foreach (['to', 'cc', 'bcc', 'replyTo'] as $type) {
            foreach ($envelope->{$type} as $address) {
                $this->{$type}($address->address, $address->name);
            }
        }

        if ($envelope->subject) {
            $this->subject($envelope->subject);
        }

        foreach ($envelope->tags as $tag) {
            $this->tag($tag);
        }

        foreach ($envelope->metadata as $key => $value) {
            $this->metadata($key, $value);
        }

        foreach ($envelope->using as $callback) {
            $this->withSymfonyMessage($callback);
        }
    }

    /**
     * Ensure the mailable's content is hydrated from the "content" method.
     *
     * @return void
     */
    private function ensureContentIsHydrated()
    {
        if (! method_exists($this, 'content')) {
            return;
        }

        $content = $this->content();

        if ($content->view) {
            $this->view($content->view);
        }

        if ($content->html) {
            $this->view($content->html);
        }

        if ($content->text) {
            $this->text($content->text);
        }

        if ($content->markdown) {
            $this->markdown($content->markdown);
        }

        if ($content->htmlString) {
            $this->html($content->htmlString);
        }

        foreach ($content->with as $key => $value) {
            $this->with($key, $value);
        }
    }

    /**
     * Ensure the mailable's attachments are hydrated from the "attachments" method.
     *
     * @return void
     */
    private function ensureAttachmentsAreHydrated()
    {
        if (! method_exists($this, 'attachments')) {
            return;
        }

        $attachments = $this->attachments();

        (new Collection(is_object($attachments) ? [$attachments] : $attachments))
            ->each(function ($attachment) {
                $this->attach($attachment);
            });
    }
}
