<?php

namespace App\Support;

use Illuminate\Foundation\Vite;

/**
 * The site is served from the project root (above core/), so Vite's build
 * manifest lives in ../build instead of core/public/build.
 */
class RootVite extends Vite
{
    protected function manifestPath($buildDirectory)
    {
        return dirname(base_path()) . '/' . $buildDirectory . '/' . $this->manifestFilename;
    }
}
