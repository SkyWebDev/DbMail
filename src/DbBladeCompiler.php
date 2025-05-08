<?php

namespace SkyWebDev\DbMail;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Compilers\BladeCompiler;

/**
 * Class DbBladeCompiler
 */
class DbBladeCompiler extends BladeCompiler
{
    /**
     * Compile the view at the given path.
     *
     * @param  string|null  $path
     * @return void
     */
    public function compile($path = null)
    {
        if ($path) {
            $this->setPath($path);
        }

        if (! is_null($this->cachePath)) {
            if (strpos($path, 'views/email')) {
                $template_name = explode('.', pathinfo($path, PATHINFO_FILENAME))[0];
                $value = DB::table('blade_templates')
                    ->where('template_name', $template_name)
                    ->value('body');
            }
            else {
                $value = $this->files->get($this->getPath());
            }
            $contents = $this->compileString($value);

            if (! empty($this->getPath())) {
                $contents = $this->appendFilePath($contents);
            }

            $this->ensureCompiledDirectoryExists(
                $compiledPath = $this->getCompiledPath($this->getPath())
            );

            $this->files->put($compiledPath, $contents);
        }
    }
}
