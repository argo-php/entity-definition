<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition\Flag;

final class ClassFlag
{
    public const int IS_ABSTRACT = 16;
    public const int IS_FINAL = 32;
    public const int IS_READONLY = 65536;

    public const int IS_DEPRECATED = 1024;
}
