<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\Support;

use Argo\DocBlockParser\PhpDoc;
use Argo\DocBlockParser\Tags\TemplateTag;
use Argo\EntityDefinition\Collection\TemplateCollection;
use Argo\EntityDefinition\Definition\TemplateDefinition;

trait TemplatesTrait
{
    private function getTemplates(?PhpDoc $docBlock): TemplateCollection
    {
        if ($docBlock === null) {
            return new TemplateCollection();
        }

        $templateTags = $docBlock->getTagsByType(TemplateTag::class);
        $templates = array_map(
            fn(TemplateTag $tag) => new TemplateDefinition(
                name: $tag->name,
                bound: $tag->isCovariant ? $tag->bound : ($tag->isContravariant ? $tag->lowerBound : null),
                default: $tag->default,
                isCovariant: $tag->isCovariant,
                isContravariant: $tag->isContravariant,
                description: $tag->description,
            ),
            $templateTags,
        );

        return new TemplateCollection($templates);
    }
}
