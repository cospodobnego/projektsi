<?php
/**
 * Post type.
 */

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\DataTransformer\TagDataTransformer;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostType.
 *
 */
class PostType extends AbstractType
{


    /**
     * Tag data transformer.
     *
     * @var \App\Form\DataTransformer\TagDataTransformer
     */
    private $tagDataTransformer;

    /**
     * TaskType constructor.
     *
     * @param \App\Form\DataTransformer\TagDataTransformer $tagDataTransformer Tag data transformer
     */
    public function __construct(TagDataTransformer $tagDataTransformer)
    {
        $this->tagDataTransformer = $tagDataTransformer;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'name',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );
        $builder->add(
            'text',
            TextareaType::class,
            [
                'label' => 'text',
                'required' => true,

            ]
        );
        $builder->add('comment', EntityType::class, [
            'class' => Comment::class,
            'label' => 'post_comment',
            'choice_label' => 'name',
        ]);
        $builder->add(
            'category',
           EntityType::class,
            [
                'label' => 'category',
                'required' => true,
                'class'=> Category::class,
                'choice_label'=> function($category){
                    return $category->getName();
                }
            ]
        );
        $builder->add(
            'tag',
          TextType::class,
            [

                'label' => 'tag',
                'attr' => ['max_length' => 255],
                'required' => false,

            ]
        );
        $builder->get('tag')->addModelTransformer(
            $this->tagDataTransformer
        );
    }


    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Post::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'post';
    }
}