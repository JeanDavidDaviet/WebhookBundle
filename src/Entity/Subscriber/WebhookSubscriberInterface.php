<?php

namespace Jeandaviddaviet\WebhookBundle\Entity\Subscriber;

interface WebhookSubscriberInterface
{
    public function getId(): mixed;

    public function getUrl(): ?string;

    public function setUrl(string $url): void;

    public function getSecret(): ?string;

    public function setSecret(string $secret): void;
}
