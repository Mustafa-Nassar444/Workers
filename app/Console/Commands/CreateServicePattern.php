<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class CreateServicePattern extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service Class';

    /**
     * Execute the console command.
     *
     * @return int
     */

    protected $file;
    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->file=$file;
    }

    public function makeDir($path){
        $this->file->makeDirectory($path,0777,true,true);
        return $path;
    }
    public function singleClassName($name){
        return ucwords(Pluralizer::singular($name));
    }

    public function stubPath(){
        return __DIR__."/../../../stubs/service.stub";
    }

    public function stubVariables(){
        return [
            'name'=>$this->singleClassName($this->argument('classname')),
        ];
    }

    public function stubContents($stubPath,$stubVariables){

        $content=file_get_contents($stubPath);
        foreach ($stubVariables as $key => $name){
            $contents=str_replace("{{".$key."}}",$name,$content);
        }
        return $contents;
    }

    public function getPath(){
        return base_path("App/Services/").$this->singleClassName($this->argument('classname'))."Service.php";
    }

    public function handle()
    {
        $path=$this->getPath();
        $this->makeDir(dirname($path));
        if($this->file->exists($path)){
            $this->info('already exists');
        }
        $stubPath=$this->stubPath();
        $content=$this->stubContents($stubPath,$this->stubVariables());
        $this->file->put($path,$content);
        $this->info('File has been created successfully');
    }
}
