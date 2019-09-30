<?php
namespace DawBed\UserBundle\Form;

use App\Entity\Product\Category;
use App\Entity\Product\Product;
use DawBed\PHPClassProvider\ClassProvider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email');
        $builder->add('password', EntityType::class, [
            'class' => Category::class
        ]);
        $builder->add('category', CollectionType::class, [
            'entry_type' => ClassProvider::get(Status)
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}