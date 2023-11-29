<?php
declare(strict_types=1);

namespace App;

class ViewResponse implements Response
{
    private string $viewName;
    private array $data;

    public function __construct(string $viewName, array $data = [])
    {
        $this->viewName = $viewName;
        $this->data = $data;
    }

    public function getViewName(): string
    {
        return $this->viewName;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $viewName
     */
    public function setViewName(string $viewName): void
    {
        $this->viewName = $viewName;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
