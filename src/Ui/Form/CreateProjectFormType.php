<?php

declare(strict_types=1);

namespace PHPMate\Ui\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CreateProjectFormType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('remote_repository_uri', TextType::class, [
            'label' => 'Gitlab git repository:',
            'attr' => [
                'placeholder' => 'https://gitlab.com/phpmate/phpmate.git',
            ],
        ]);

        $builder->add('personal_access_token', TextType::class, [
            'label' => 'Personal Access Token:',
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /*
        $optionsResolver->setDefaults([
            'data_class' => ContactMessage::class,
        ]);
        */
    }
}