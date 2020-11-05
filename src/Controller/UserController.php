<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class UserController extends AbstractController

{
public function cabinet()
{
    return $this->render('Cabinet/index.html.twig');
}

public function addgoods()
{
    return $this->render('goods/goods.html.twig');
}

public function mygoods()
{
    return $this->render('goods/mygoods.html.twig');
}


}