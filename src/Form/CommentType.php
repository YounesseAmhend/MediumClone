<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('timestamp')
            ->add('commentedPost', EntityType::class, [
                'class' => Post::class,
                'choice_label' => 'id',
                'label' => 'Post',
            ])
        ->add('user_c', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id', 
                'label' => 'User',
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
