<?php

declare(strict_types=1);

namespace Jeandaviddaviet\WebhookBundle\Services;

use Jeandaviddaviet\WebhookBundle\Repository\Subscriber\WebhookSubscriberRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Webhook\Messenger\SendWebhookMessage;
use Symfony\Component\Webhook\Subscriber;

class WebhookNotifier
{
    public function __construct(
        private readonly MessageBusInterface $bus,
//        private readonly WebhookSubscriberRepositoryInterface $webhookSubscriberRepository,
        private readonly NormalizerInterface $normalizer,
    )
    {
    }

    public function notify(mixed $entity, string $eventName): void
    {
//        $webhookSubscriber = $this->webhookSubscriberRepository->find(1);
//        $subcriber = new Subscriber($webhookSubscriber->getUrl(), $webhookSubscriber->getSecret());
        $subcriber = new Subscriber('url', 'secret');
        $remoteEventName = sprintf('%s_%s', $entity::class, $eventName);
//        $remoteEventId = (string) (new Ulid());
        $remoteEventId = '1';
        $payload = $this->normalizer->normalize($entity);
        $event = new RemoteEvent($remoteEventName, $remoteEventId, $payload);
        dd($event);
        $this->bus->dispatch(new SendWebhookMessage($subcriber, $event));
    }
}
