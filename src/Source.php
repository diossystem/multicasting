<?php

namespace Dios\System\Multicasting;

/**
 * It used for keeping sources of an entity key.
 */
class Source
{
    /**
     * A type is kept in the current model.
     */
    const CURRENT_MODEL_TYPE = 0;

    /**
     * A type is kept in another model.
     */
    const ANOTHER_MODEL_TYPE = 1;

    /**
     * An original source;
     *
     * @var string
     */
    protected $source;

    /**
     * A current source type.
     *
     * @var int
     */
    protected $type;

    /**
     * A real source.
     *
     * @var string
     */
    protected $realSource;

    /**
     * A source of key.
     *
     * @var string
     */
    protected $sourceOfKey;

    /**
     * Initializes an instance with a source of the entity.
     *
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->source = $source;
        $this->type = $this->defineSources($source);
    }

    /**
     * Returns the current source type.
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Returns the real source where type keeps.
     *
     * @return string
     */
    public function getRealSource()
    {
        return $this->realSource;
    }

    /**
     * Returns the source of an entity key.
     *
     * @return string
     */
    public function getSourceOfKey(): string
    {
        return $this->sourceOfKey;
    }

    /**
     * Detectes a source type from the given string.
     *
     * @param  string $source
     * @return int
     */
    public static function detectType(string $source): int
    {
        $sources = explode('|', $source, 2);

        return count($sources) === 2
            ? self::ANOTHER_MODEL_TYPE
            : self::CURRENT_MODEL_TYPE
        ;
    }

    /**
     * Defines the sources and returns a detected source type.
     *
     * @param  string $source
     * @return int
     */
    protected function defineSources(string $source): int
    {
        $sources = explode('|', $source, 2);
        $type = self::CURRENT_MODEL_TYPE;

        if (count($sources) === 2) {
            list($this->realSource, $this->sourceOfKey) = $sources;
            $type = self::ANOTHER_MODEL_TYPE;
        } else {
            $this->realSource = $sources[0];
            $this->sourceOfKey = $sources[0];
        }

        return $type;
    }
}
