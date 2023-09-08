<?php

namespace App\Twig;

use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormViewPropertyGetterExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction('formview_prop', array($this, 'getFormViewProperty')),
        );
    }

    public function getFormViewProperty(FormView $formView, string $prop)
    {
        // parent, children or vars
        return $formView->{$prop};
    }
}
