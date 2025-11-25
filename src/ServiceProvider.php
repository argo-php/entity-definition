<?php

declare(strict_types=1);

namespace Argo\EntityDefinition;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * @api
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * @psalm-suppress InvalidFunctionCall
     */
    public function register(): void
    {
        $config = (new ConfigProvider())();
        foreach ($config['dependencies'] as $abstract => $concrete) {
            if (!is_string($concrete) || !class_exists($concrete)) {
                continue;
            }

            if (method_exists($concrete, '__invoke')) {
                $this->app->singleton(
                    $abstract,
                    fn(Container $container): mixed => $this->app->make($concrete)($container),
                );
            } else {
                $this->app->singleton($abstract, $concrete);
            }
        }
    }

}
