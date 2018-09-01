<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ["label" => "Nome"], [
                'required' => true
            ])
            ->add('last_name', TextType::class, ['label' => 'Sobrenome'], [
                'required' => true
            ])
            ->add('user', EntityType::class, [
                "label" => "Usuário",
                'class' => User::class,
                'choice_label' => 'email'
            ])
            ->add('github_url', UrlType::class, ['label' => 'URL GitHub'])
            ->add('phone', TextType::class, ['label' => 'Telefone'])
            ->add('status', CheckboxType::class, [
                'label' => 'Membro Ativo'
            ])
            ->add('btn_salvar', SubmitType::class, [
                'label' => 'Salvar'
            ])
            ->add('btn_consultar', SubmitType::class, [
                'label' => "Consultar"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
