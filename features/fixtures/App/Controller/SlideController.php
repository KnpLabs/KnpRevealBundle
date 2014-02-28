<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\RevealBundle\Controller\SlideTrait;

class SlideController extends Controller
{
    use SlideTrait;

    public function getSlidesDirectory()
    {
        return "@App/Resources/views/Slide";
    }
}