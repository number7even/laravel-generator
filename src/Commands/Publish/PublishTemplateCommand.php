<?php

namespace Number7even\Generator\Commands\Publish;

class PublishTemplateCommand extends PublishBaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'Number7even.publish:templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes api generator templates.';

    private $templatesDir;

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->templatesDir = config(
            'Number7even.laravel_generator.path.templates_dir',
            base_path('resources/Number7even/Number7even-generator-templates/')
        );

        if ($this->publishGeneratorTemplates()) {
            $this->publishScaffoldTemplates();
        }
    }

    /**
     * Publishes templates.
     */
    public function publishGeneratorTemplates()
    {
        $templatesPath = __DIR__.'/../../../templates';

        return $this->publishDirectory($templatesPath, $this->templatesDir, 'Number7even-generator-templates');
    }

    /**
     * Publishes templates.
     */
    public function publishScaffoldTemplates()
    {
        $templateType = config('Number7even.laravel_generator.templates', 'core-templates');

        $templatesPath = base_path('vendor/Number7evenlabs/'.$templateType.'/templates/scaffold');

        return $this->publishDirectory($templatesPath, $this->templatesDir.'/scaffold', 'Number7even-generator-templates/scaffold', true);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}
