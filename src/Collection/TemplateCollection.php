<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Collection;

use Argo\EntityDefinition\Definition\TemplateDefinition;

/**
 * @api
 */
readonly class TemplateCollection
{
    /** @var array<string, TemplateDefinition> */
    private array $templates;

    /**
     * @param array<TemplateDefinition> $templates
     */
    public function __construct(array $templates = [])
    {
        $result = [];
        foreach ($templates as $template) {
            $result[$template->name] = $template;
        }

        $this->templates = $result;
    }

    /**
     * @return array<string, TemplateDefinition>
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    public function merge(TemplateCollection $templates): TemplateCollection
    {
        return new self(array_merge($this->templates, $templates->templates));
    }

    public function getByName(string $name): ?TemplateDefinition
    {
        return $this->templates[$name] ?? null;
    }

    public function hasByName(string $name): bool
    {
        return array_key_exists($name, $this->templates);
    }
}
