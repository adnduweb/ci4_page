<?php namespace Spreadaurora\Ci4_page\Entities;

use CodeIgniter\Entity;

class Page extends Entity
{
    protected $datamap = [];
    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = [];
    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [];

}
