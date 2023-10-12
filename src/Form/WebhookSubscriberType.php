<?php

namespace Jeandaviddaviet\WebhookBundle\Form;

use Jeandaviddaviet\WebhookBundle\Entity\Subscriber\WebhookSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class WebhookSubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Url(),
                ]
            ])
            ->add('secret', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebhookSubscriberInterface::class,
        ]);
    }
}
