<?php
namespace App\Controller\Traits;

use Symfony\Component\HttpFoundation\Request;

trait initForm{
    private function initForm(Request $request, $formType, $object): array
    {
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);
        $view = $form->createView();
        return ([
            "form" => $form,
            "view" => $view
        ]);
    }
}

