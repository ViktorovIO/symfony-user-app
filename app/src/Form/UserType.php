<?php

namespace App\Form;

use App\Entity\User;
use App\Model\Enum\UserRolesEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('personalNumber')
            ->add('lastName')
            ->add('firstName')
            ->add('surname', null, [
                'required' => false,
            ])
            ->add('phoneList', TextType::class, [
                'help' => 'You can add few phone numbers ("," as delimiter)',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    UserRolesEnum::USER->name => UserRolesEnum::USER->value,
                    UserRolesEnum::ADMIN->name => UserRolesEnum::ADMIN->value,
                ],
            ])
            ->add('password', PasswordType::class)
        ;

        $builder->get('phoneList')
            ->addModelTransformer(new CallbackTransformer(
                function ($phonesAsArray) {
                    return trim(implode(',', (array)$phonesAsArray));
                },
                function ($phonesAsString) {
                    $string = trim($phonesAsString);
                    if (empty($string)) {
                        return [];
                    }

                    $values = explode(',', $string);

                    if (count($values) === 0) {
                        return [];
                    }

                    foreach ($values as &$value) {
                        $value = trim($value);
                    }

                    return $values;
                },
            ));
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray) {
                    return trim(implode(',', (array)$rolesAsArray));
                },
                function ($rolesAsString) {
                    $string = trim($rolesAsString);
                    if (empty($string)) {
                        return [];
                    }

                    $values = explode(',', $string);

                    if (count($values) === 0) {
                        return [];
                    }

                    foreach ($values as &$value) {
                        $value = trim($value);
                    }

                    return $values;
                },
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
