<?php

namespace Number7even\Generator\Generators\Scaffold;

use Number7even\Generator\Common\CommandData;
use Number7even\Generator\Generators\BaseGenerator;
use Number7even\Generator\Utils\FileUtil;

class TranslationGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path_de;
	private $path_en;

    /** @var string */
    private $createDeFileName;
	private $createEnFileName;

    /** @var string */
    private $updateFileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path_de = $commandData->config->pathTranslationDe;
		$this->path_en = $commandData->config->pathTranslationEn;
        $this->createDeFileName = $this->commandData->modelName.'.php';
        $this->createEnFileName = $this->commandData->modelName.'.php';
    }

    public function generate()
    {
        $this->generateCreateTranslation();
    }

    private function generateCreateTranslation()
    {
        $templateData_de = get_template('scaffold.translation.lang_translation', 'laravel-generator');

        $templateData_de = fill_template($this->commandData->dynamicVars, $templateData_de);

        FileUtil::createFile($this->path_de, $this->createDeFileName, $templateData_de);

        $this->commandData->commandComment("\nCreate de translation created: ");
        $this->commandData->commandInfo($this->createDeFileName);
		
		$templateData_en = get_template('scaffold.translation.lang_translation', 'laravel-generator');

        $templateData_en = fill_template($this->commandData->dynamicVars, $templateData_en);

        FileUtil::createFile($this->path_en, $this->createEnFileName, $templateData_en);

        $this->commandData->commandComment("\nCreate en translation created: ");
        $this->commandData->commandInfo($this->createEnFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path_de, $this->createDeFileName)) {
            $this->commandData->commandComment('Create DE translation file deleted: '.$this->createDeFileName);
        }
		
		if ($this->rollbackFile($this->path_en, $this->createEnFileName)) {
            $this->commandData->commandComment('Create EN translation file deleted: '.$this->createEnFileName);
        }
    }
}
