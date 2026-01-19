<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\View;

class StaticController
{
    public function about(): void
    {
        View::render('about');
    }

    public function methodology(): void
    {
        View::render('methodology');
    }

    public function privacy(): void
    {
        View::render('privacy');
    }

    public function terms(): void
    {
        View::render('terms');
    }
}
