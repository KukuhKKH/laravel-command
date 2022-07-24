<?php
namespace Kukuhkkh\LaravelCommand\Commands;

use Kukuhkkh\LaravelCommand\Support\GenerateFile;
use Kukuhkkh\LaravelCommand\Support\FileGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class CreateEnumCommand extends CommandGenerator
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
    protected $name = 'make:enum';

    /**
     * command description.
     * description
     * @var string
     */
    protected $description = 'Command description';
    
    /**
     * Get Command argumant EX : HasAuth
     * getArguments
     *
     * @return void
     */
    protected function getArguments()
    {
        return [
            ['enum', InputArgument::REQUIRED, 'The name of the enum'],
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
        return app_path()."/Enums".'/'. $this->getEnumName() . '.php';
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
        return "App\\Enums";
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
        $path = str_replace('\\', '/', $this->getDestinationFilePath());


        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            
            (new FileGenerator($path, $contents))->generate();

            $this->info("Horeeeeee :) : {$path}");
        } catch (\Exception $e) {
            
            $this->error("File : {$e->getMessage()}");

            return E_ERROR;
        }

        return 0;

    }

}
