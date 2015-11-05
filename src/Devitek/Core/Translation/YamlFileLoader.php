<?php namespace Devitek\Core\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Symfony\Component\Yaml\Parser;
use File;

use Illuminate\Translation\LoaderInterface;

class YamlFileLoader implements LoaderInterface
{

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The default path for the loader.
     *
     * @var string
     */
    protected $path;

    /**
     * All of the namespace hints.
     *
     * @var array
     */
    protected $hints = [];

    /**
     * Create a new file loader instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string  $path
     * @return void
     */
    public function __construct(Filesystem $files, $path)
    {
        $this->path = $path;
        $this->files = $files;
    }
    
    
    /**
     * Get Allowed Translation File Extensions.
     *
     * @return array
     */
    protected function getAllowedFileExtensions()
    {
        return ['php', 'yml', 'yaml'];
    }
    
    
    /**
     * Load a local namespaced translation group for overrides.
     *
     * @param  array  $lines
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
	protected function loadNamespaceOverrides(array $lines, $locale, $group, $namespace)
	{
		foreach ($this->getAllowedFileExtensions() as $extension) {
			
			$file = "{$this->path}/vendor/{$namespace}/{$locale}/{$group}.{$extension}";
	
			if ($this->files->exists($file))
			{
				return array_replace_recursive($lines, $this->parseContent($extension, $file));
			}
			
		}
		
		return $lines;
		
	}


    /**
     * Load a locale from a given path.
     *
     * @param  string  $path
     * @param  string  $locale
     * @param  string  $group
     * @return array
     */
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


     /**
     * require reqular php file or parse yaml before loading it
     *
     * @param  string  $extension
     * @param  string  $file
     * @return array
     */
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


     /**
     * Parse a Yaml file and return as a php array or load from cache.
     *
     * @param  string  $file
     * @return array
     */
    protected function parseYamlOrLoadFromCache($file)
    {

		$cachedir = storage_path() . '/yaml-translation/';
	        
	    $cachefile = $cachedir . 'cache.' . md5($file) . '.php';

        if (@filemtime($cachefile) < filemtime($file)) {
	        
            $parser  = new Parser();
            $content = null === ($yaml = $parser->parse(file_get_contents($file))) ? [] : $yaml;
            if ( !file_exists($cachedir) ){
	            @mkdir($cachedir, 0755);
	        }
            file_put_contents($cachefile, "<?php" . PHP_EOL . PHP_EOL . "return " . var_export($content, true) . ";");     
            
        } else {
	     	
            $content = require $cachefile;

        }

        return $content;
    }
    
    
     /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        if (is_null($namespace) || $namespace == '*') {
            return $this->loadPath($this->path, $locale, $group);
        }

        return $this->loadNamespaced($locale, $group, $namespace);
    }
	
	
	/**
     * Load a namespaced translation group.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    protected function loadNamespaced($locale, $group, $namespace)
    {
        if (isset($this->hints[$namespace])) {
            $lines = $this->loadPath($this->hints[$namespace], $locale, $group);

            return $this->loadNamespaceOverrides($lines, $locale, $group, $namespace);
        }

        return [];
    }
    
    
     /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */    
    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
    }
    
}
