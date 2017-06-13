<?php

namespace Number7even\Generator\Generators\Scaffold;

use Number7even\Generator\Common\CommandData;
use Number7even\Generator\Generators\BaseGenerator;
use Number7even\Generator\Utils\FileUtil;

class ControllerGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $templateType;

    /** @var string */
    private $fileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathController;
        $this->templateType = config('Number7even.laravel_generator.templates', 'core-templates');
        $this->fileName = $this->commandData->modelNameStudlyCase.'Controller.php';
    }

    public function generate()
    {
        if ($this->commandData->getAddOn('datatables')) {
            $templateData = get_template('scaffold.controller.datatable_controller', 'laravel-generator');

            $this->generateDataTable();
        } else {
            $templateData = get_template('scaffold.controller.controller', 'laravel-generator');

            $paginate = $this->commandData->getOption('paginate');

            if ($paginate) {
                $templateData = str_replace('$RENDER_TYPE$', 'paginate('.$paginate.')', $templateData);
            } else {
                $templateData = str_replace('$RENDER_TYPE$', 'all()', $templateData);
            }
        }

        $module_config = \DB::table('modbuilder_mob')->where('slug_mob','=',$this->commandData->modelName)->first();
        $mod = json_decode($module_config->module_config);
        

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        //print_r($templateData);
        $validationStr ="\n\t".'$this->validate($request, ['."\n";
        if(isset($mod->module_info->fields) && $mod->module_info->fields!=''){
            $fieldsArr = json_decode($mod->module_info->fields);
            foreach($fieldsArr as $fieldKey => $fieldValue) {
                 //$actualJsonFormat[$fieldKey]['validation'] = $fieldValue->name;

                
                if(isset($fieldValue->validation)){
                    $validationValueStr = '';
                    foreach($fieldValue->validation as $validationKey => $validationValue) {
                       $validationValueStr.=$validationValue->rule.'|'; 
                    }
                
                    $validationStr .= "\t\t"."'".$fieldValue->name."' => '".substr($validationValueStr,0,-1)."',"."\n";  
                } 
            } 
            $validationStr = substr($validationStr,0,-1)."\n";
            $validationStr .="\t".']);'."\n";   
        }
        $templateData = str_replace('$VAILDATIONS$', $validationStr, $templateData);
        $templateData = str_replace('$EDIT_VAILDATIONS$', $validationStr, $templateData);
        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nController created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    private function generateDataTable()
    {
        $templateData = get_template('scaffold.datatable', 'laravel-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        $headerFieldTemplate = get_template('scaffold.views.datatable_column', $this->templateType);

        $headerFields = [];

        foreach ($this->commandData->fields as $field) {
            if (!$field->inIndex) {
                continue;
            }
            $headerFields[] = $fieldTemplate = fill_template_with_field_data(
                $this->commandData->dynamicVars,
                $this->commandData->fieldNamesMapping,
                $headerFieldTemplate,
                $field
            );
        }

        $path = $this->commandData->config->pathDataTables;

        $fileName = $this->commandData->modelName.'DataTable.php';

        $fields = implode(','.infy_nl_tab(1, 3), $headerFields);

        $templateData = str_replace('$DATATABLE_COLUMNS$', $fields, $templateData);

        FileUtil::createFile($path, $fileName, $templateData);

        $this->commandData->commandComment("\nDataTable created: ");
        $this->commandData->commandInfo($fileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('Controller file deleted: '.$this->fileName);
        }

        if ($this->commandData->getAddOn('datatables')) {
            if ($this->rollbackFile($this->commandData->config->pathDataTables, $this->commandData->modelName.'DataTable.php')) {
                $this->commandData->commandComment('DataTable file deleted: '.$this->fileName);
            }
        }
    }
}
