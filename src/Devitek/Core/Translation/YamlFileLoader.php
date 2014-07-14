<?php namespace Devitek\Core\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Symfony\Component\Yaml\Parser;

class YamlFileLoader extends FileLoader
{
    protected function getAllowedFileExtensions()
    {
        return ['php', 'yml', 'yaml'];
    }

    protected function loadNamespaceOverrides(array $lines, $locale, $group, $namespace)
    {
        foreach ($this->getAllowedFileExtensions() as $extension) {
            $file = "{$this->path}/packages/{$locale}/{$namespace}/{$group}." . $extension;

            if ($this->files->exists($file)) {
                return $this->replaceLines($extension, $lines, $file);
            }
        }

        return $lines;
    }

    protected function replaceLines($format, $lines, $file)
    {
        return array_replace_recursive($lines, $this->parseContent($format, $file));
    }

    protected function parseContent($format, $file)
    {
        $content = null;

        switch ($format) {
            case 'php':
                $content = $this->files->getRequire($file);
                break;
            case 'yml':
            case 'yaml':
                $content = $this->parseYamlOrLoadFromCache($file);
                break;
        }

        return $content;
    }

    protected function loadPath($path, $locale, $group)
    {
        foreach ($this->getAllowedFileExtensions() as $extension) {
            if ($this->files->exists($full = "{$path}/{$locale}/{$group}." . $extension)) {
                return $this->parseContent($extension, $full);
            }
        }

        return [];
    }
    
    protected function parseYamlOrLoadFromCache($file)
    {
        $cachefile = storage_path() . '/cache/yaml.lang.cache.' . md5($file) . '.php';

        if (@filemtime($cachefile) < filemtime($file)) {

            $parser = new Parser();
            $content = $parser->parse(file_get_contents($file));
            file_put_contents($cachefile, "<?php \r\n\r\n return " . var_export($content, true) . ";");

        } else {

            $content = $this->files->getRequire($cachefile);

        }
        return $content;
    }
    
}
