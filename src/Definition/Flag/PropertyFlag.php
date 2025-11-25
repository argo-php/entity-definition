<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition\Flag;

final class PropertyFlag
{
    public const int IS_STATIC = 16;

    public const int IS_PUBLIC = 1;
    public const int IS_PROTECTED = 2;
    public const int IS_PRIVATE = 4;

    public const int IS_READONLY = 65536;

    public const int IS_DEPRECATED = 1024;
    public const int IS_PROMOTED = 2048;
}
