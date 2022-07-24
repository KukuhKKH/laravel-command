<?php
namespace Kukuhkkh\LaravelCommand\Commands;

use Kukuhkkh\LaravelCommand\Support\GenerateFile;
use Kukuhkkh\LaravelCommand\Support\FileGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;
class CreateModuleEnumCommand extends CommandGenerator
{

    
    /**
     * argumentName
     *
     * @var string
     */
    public $argumentName = 'enum';

    /**
     * Name and signiture of Command.
     * name
     * @var string
     */
    protected $name = 'module:make-enum';

     /**
     * command description.
     * description
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Get command agrumants - EX : HasAuth
     * getArguments
     *
     * @return void
     */
    protected function getArguments()
    {
        return [
            ['enum', InputArgument::REQUIRED, 'The name of the enum'],
            ['module', InputArgument::REQUIRED, 'The name of module will be used.']
        ];
    }

        
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
       parent::__construct();
    }

        
    /**
     * getEnumName
     *
     * @return void
     */
    private function getEnumName()
    {
        $enum = Str::studly($this->argument('enum'));
        return $enum;
    }
    
    /**
     * getDestinationFilePath
     *
     * @return void
     */
    protected function getDestinationFilePath()
    {
        return base_path()."/Modules/{$this->argument('module')}"."/Enums".'/'. $this->getEnumName() . '.php';
    }
    

    /**
     * getEnumNameWithoutNamespace
     *
     * @return void
     */
    private function getEnumNameWithoutNamespace()
    {
        return class_basename($this->getEnumName());
    }
    
    /**
     * getDefaultNamespace
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return "Modules\\{$this->argument('module')}\\Enums";
    }

    
    /**
     * getStubFilePath
     *
     * @return void
     */
    protected function getStubFilePath()
    {
        $stub = '/stubs/enums.stub';
        return $stub;
    }
    
    /**
     * getTemplateContents
     *
     * @return void
     */
    protected function getTemplateContents()
    {
        return (new GenerateFile(__DIR__.$this->getStubFilePath(), [
            'CLASS_NAMESPACE'   => $this->getClassNamespace(),
            'CLASS'             => $this->getEnumNameWithoutNamespace()
        ]))->render();
    }
  
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check this module exists or not.
        if ($this->checkModuleExists($this->argument('module')) === false) {
            $this->error(" Module [{$this->argument('module')}] does not exist!");  
            return E_ERROR;
            exit;
         }
         
        $path = str_replace('\\', '/', $this->getDestinationFilePath());


        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            
            (new FileGenerator($path, $contents))->generate();

            $this->info("Created : {$path}");
        } catch (\Exception $e) {

            $this->error("File : {$e->getMessage()}");

            return E_ERROR;
        }

        return 0;

    }
}
