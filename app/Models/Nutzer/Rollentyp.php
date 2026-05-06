<?php

namespace App\Models\Nutzer;

// https://stitcher.io/blog/php-enums

enum Rollentyp: string {

    case ADMIN = 'Admin';
    case LEHRENDER = 'Lehrender';
    case STUDENT = 'Student';

}