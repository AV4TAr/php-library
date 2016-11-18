<?php

class RoboFile extends \Robo\Tasks
{

    public function watch()
    {
        $this->taskWatch()
            ->monitor([
            'src',
            'tests/Common'
        ], function ($csfix) {
            $this->test();
            $this->cs();
        })
            ->run();
    }

    public function test()
    {
        $this->taskComposerDumpAutoload()
            ->optimize()
            ->run();
        $this->taskExec('./vendor/bin/phpunit')->run();
    }

    public function cs()
    {
        $this->taskExec('./vendor/bin/phpcs')->run();
    }

    public function changelog($version)
    {
        $this->taskChangelog()
            ->version($version)
            ->askForChanges()
            ->run();
    }

    public function release()
    {
        $version = file_get_contents('VERSION');
        // ask for changes in this release
        $changelog = $this->taskChangelog()
            ->version($version)
            ->askForChanges()
            ->run();
        // adding changelog and pushing it
        $this->taskGitStack()
            ->add('CHANGELOG.md')
            ->commit('updated changelog')
            ->push()
            ->run();
    }
}