<?php


namespace Waxwink\DocGen;


use ReflectionClass;
use Waxwink\DocGen\Exceptions\ResourceClassNotFoundException;

class Resource
{
    /**
     * @var bool
     */
    protected $lock = [];

    /**
     * @var array
     */
    protected $keys = false;

    /**
     * @param $resourceClass
     * @return array
     * @throws ResourceClassNotFoundException
     * @throws \ReflectionException
     */
    public function keys($resourceClass)
    {
        if (!class_exists($resourceClass))
            throw new ResourceClassNotFoundException("$resourceClass does not exist in project");

        $this->updateKeys($resourceClass);

        return $this->keys;

    }

    /**
     * @param ReflectionClass $ref
     * @return int
     * @throws \ReflectionException
     */
    protected function startLine(ReflectionClass $ref): int
    {
        return $ref->getMethod("toArray")->getStartLine();

    }

    /**
     * @param ReflectionClass $ref
     * @return int
     * @throws \ReflectionException
     */
    protected function length(ReflectionClass $ref)
    {
        return $ref->getMethod("toArray")->getEndLine() - $this->startLine($ref);
    }

    /**
     * @param ReflectionClass $ref
     * @return array
     * @throws \ReflectionException
     */
    protected function lines(ReflectionClass $ref): array
    {
        return array_slice(file($ref->getFileName()), $this->startLine($ref), $this->length($ref));

    }

    /**
     * @param $line
     */
    protected function checkCommentStart($line): void
    {
        if (strpos($line, "/*"))
            $this->lock = true;
    }

    /**
     * @param $matches
     */
    protected function addKey($matches): void
    {
        if (!$this->lock)
            $this->keys[] = str_replace(['"', '\''], '', trim($matches[1]));
    }

    /**
     * @param $line
     */
    protected function checkCommentEnd($line): void
    {
        if (strpos($line, "*/"))
            $this->lock = false;
    }

    /**
     * @param $class
     * @throws \ReflectionException
     */
    protected function updateKeys($class)
    {
        $this->loopThroughLines(new ReflectionClass($class));
    }

    /**
     * @param ReflectionClass $ref
     * @throws \ReflectionException
     */
    protected function loopThroughLines(ReflectionClass $ref)
    {
        foreach ($this->lines($ref) as $line) {
            $this->checkCommentStart($line);

            if (preg_match("/(.*)=>/", $line, $matches))
                $this->addKey($matches);

            $this->checkCommentEnd($line);
        }
    }

}