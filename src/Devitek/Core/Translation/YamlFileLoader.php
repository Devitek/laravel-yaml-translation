<?php namespace Devitek\Core\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Symfony\Component\Yaml\Parser;
use File;

class YamlFileLoader extends FileLoader
{

    protected function getAllowedFileExtensions()
    {
        return ['php', 'yml', 'yaml'];
    }
    
    
	protected function loadNamespaceOverrides(array $lines, $locale, $group, $namespace)
	{
		foreach ($this->getAllowedFileExtensions() as $extension) {
			
			$file = "{$this->path}/packages/{$locale}/{$namespace}/{$group}.{$extension}";
	
			if ($this->files->exists($file))
			{
				return array_replace_recursive($lines, $this->parseContent($extension, $file));
			}
			
		}
		
		return $lines;
		
	}


	protected function loadPath($path, $locale, $group)
	{
		
		foreach ($this->getAllowedFileExtensions() as $extension) {
		
			if ($this->files->exists($full = "{$path}/{$locale}/{$group}.{$extension}"))
			{
				return $this->parseContent($extension, $full);
			}
			
		}
		
		return array();
		
	}


    protected function parseContent($extension, $file)
    {
        $content = null;

        switch ($extension) {
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

    protected function parseYamlOrLoadFromCache($file)
    {

		$cachedir = storage_path() . '/yaml-translation/';
	        
	    $cachefile = $cachedir . '/cache.' . md5($file) . '.php';

        if (@filemtime($cachefile) < filemtime($file)) {
	        
            $parser  = new Parser();
            $content = null === ($yaml = $parser->parse(file_get_contents($file))) ? [] : $yaml;
            if ( !File::exists($cachedir) ){
	            File::makeDirectory($cachedir);
	        }
            File::put($cachefile, "<?php" . PHP_EOL . PHP_EOL . "return " . var_export($content, true) . ";");            
            
        } else {
	     	
            $content = require $cachefile;

        }

        return $content;
    }
    
}
