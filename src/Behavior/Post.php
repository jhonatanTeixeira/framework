<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"METHOD"})
 * @NamedArgumentConstructor
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Post
{
    /**
     * @var string
     * @required
     */
    public $path = '/';

    public function __construct(string $path = '/')
    {
        $this->path = $path;
    }
}
