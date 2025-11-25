<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition\Flag;

final class ParameterFlag
{
    public const int IS_VARIADIC = 1;
    public const int IS_PROMOTED = 2;

    public const int IS_DEPRECATED = 1024;
}
