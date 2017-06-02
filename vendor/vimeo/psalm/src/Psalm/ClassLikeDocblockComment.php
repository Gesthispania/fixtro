<?php
namespace Psalm;

class ClassLikeDocblockComment
{
    /**
     * Whether or not the class is deprecated
     * @var boolean
     */
    public $deprecated = false;

    /**
     * @var array<int, array<int, string>>
     */
    public $template_types = [];

    /**
     * @var array<int, array{name:string, type:string}>
     */
    public $properties = [];
}
