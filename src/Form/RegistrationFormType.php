<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class RegistrationFormType extends AbstractType
{
 

public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('email', EmailType::class, [
            'label' => 'Email',
        ])
        ->add('plainPassword', PasswordType::class, [
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank(['message' => 'Please enter a password']),
                new Length([
                    'min' => 4,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    'max' => 4096,
                ]),
            ],
        ])
        ->add('user_nom', TextType::class, [
            'label' => 'Full Name',
            'constraints' => [
                new NotBlank(['message' => 'Please enter your full name']),
            ],
        ])
        ->add('addresse', TextType::class, [
            'label' => 'Address',
            'required' => false,
        ])
        ->add('cp', TextType::class, [
            'label' => 'Postal Code',
            'constraints' => [
                new Regex([
                    'pattern' => '/^[0-9]{5}$/',
                    'message' => 'Please enter a valid postal code (5 digits)',
                ]),
            ],
        ])
        ->add('tel', TextType::class, [
            'label' => 'Phone Number',
            'constraints' => [
                new Regex([
                    'pattern' => '/^\+?[0-9]{10,15}$/',
                    'message' => 'Please enter a valid phone number (10-15 digits, optional "+").',
                ]),
            ],
        ])
        ->add('user_photo', FileType::class, [
            'label' => 'Profile Photo',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                        'image/ivf',
                        'image/svg',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image (JPEG or PNG)',
                ]),
            ],
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
