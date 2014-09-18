<?php

use Illuminate\Console\Command;

class StaticFilesGenerator extends Command
{
    //Имя команды для вызова функции генерации страницы
    protected $name = 'generateCss';

    protected $description = "Generate CSS-file from SCSS(SASS)";

    //Призываем конвертацию.Метод у относительным путём до файлов - не работает
    public function fire()
    {
        SassCompiler::run("public/scss/", "public/css/");
    }


}
