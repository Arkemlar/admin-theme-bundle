<?php

namespace Arkemlar\Admin\ThemeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addViewTransformer(new CallbackTransformer(
                function ($data) use ($options) {    // Transform (to view)
                    if(is_array($data) && count($data) == 2)
                        return $data[0]->format($options['format']) . ' - ' . $data[1]->format($options['format']);
                    return '';
                },
                function ($data) use ($options) {    // Reverse transform
                    [$startDate, $endDate] = explode(' - ', $data);
                    $data = [
                        \DateTime::createFromFormat($options['format'], $startDate),
                        \DateTime::createFromFormat($options['format'], $endDate)
                    ];
                    return $data;
                }
            ))
        ;
        // See widget at Arkemlar/Admin/ThemeBundle/Resources/views/admin_form_theme.html.twig => block date_range_widget
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data' => [new \DateTime("previous month"), new \DateTime()],
                'format' => 'd/m/Y',
            ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
    
    public function getBlockPrefix()
    {
        return 'date_range';
    }
}
