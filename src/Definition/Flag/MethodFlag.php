<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition\Flag;

final class MethodFlag
{
    public const int IS_PUBLIC = 1;
    public const int IS_PROTECTED = 2;
    public const int IS_PRIVATE = 4;

    public const int IS_STATIC = 16;
    public const int IS_FINAL = 32;
    public const int IS_ABSTRACT = 64;

    public const int IS_DEPRECATED = 1024;
    public const int IS_CONSTRUCTOR = 2048;
    public const int IS_DESTRUCTOR = 4096;
}
