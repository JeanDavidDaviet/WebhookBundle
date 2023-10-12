<?php

namespace Jeandaviddaviet\WebhookBundle\Controller;

use Jeandaviddaviet\WebhookBundle\Entity\Subscriber\WebhookSubscriberInterface;
use Jeandaviddaviet\WebhookBundle\Form\WebhookSubscriberType;
use Jeandaviddaviet\WebhookBundle\Repository\Subscriber\WebhookSubscriberRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

abstract class AbstractWebhookSubscriberController extends AbstractController
{
    abstract public static function getEntityFqcn(): string;

    #[Route('/', name: 'jdd_webhook_subscriber_index', methods: ['GET'])]
    public function index(WebhookSubscriberRepositoryInterface $webhookSubscriberRepository): Response
    {
        return $this->render('@Webhook/crud/index.html.twig', [
            'webhook_subscribers' => $webhookSubscriberRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'jdd_webhook_subscriber_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $webhookSubscriber = new ($this->getEntityFqcn())();
        $form = $this->createForm(WebhookSubscriberType::class, $webhookSubscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($webhookSubscriber);
            $entityManager->flush();

            return $this->redirectToRoute('jdd_webhook_subscriber_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@Webhook/crud/new.html.twig', [
            'webhook_subscriber' => $webhookSubscriber,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'jdd_webhook_subscriber_show', methods: ['GET'])]
    public function show(WebhookSubscriberInterface $webhookSubscriber): Response
    {
        return $this->render('@Webhook/crud/show.html.twig', [
            'webhook_subscriber' => $webhookSubscriber,
        ]);
    }

    #[Route('/{id}/edit', name: 'jdd_webhook_subscriber_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebhookSubscriberInterface $webhookSubscriber, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebhookSubscriberType::class, $webhookSubscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('jdd_webhook_subscriber_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@Webhook/crud/edit.html.twig', [
            'webhook_subscriber' => $webhookSubscriber,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'jdd_webhook_subscriber_delete', methods: ['POST'])]
    public function delete(Request $request, WebhookSubscriberInterface $webhookSubscriber, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$webhookSubscriber->getId(), $request->request->get('_token'))) {
            $entityManager->remove($webhookSubscriber);
            $entityManager->flush();
        }

        return $this->redirectToRoute('jdd_webhook_subscriber_index', [], Response::HTTP_SEE_OTHER);
    }
}
