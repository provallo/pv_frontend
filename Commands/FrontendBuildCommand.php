<?php

namespace ProVallo\Plugins\Frontend\Commands;

use ProVallo\Components\Command;
use ProVallo\Core;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FrontendBuildCommand extends Command
{
    
    protected function configure ()
    {
        $this->setName('frontend:build');
        $this->setDescription('Builds JS and LESS for the frontend.');
    }
    
    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $lessFiles = Core::events()->collect('frontend.register.less');
        $jsFiles   = Core::events()->collect('frontend.register.javascript');
        
        $output->writeln('===== BUILDING LESS =====');
        $targetFilename = path(__DIR__, '../Views/_resources/css/all.css');
        $css = '';
        
        foreach ($lessFiles as $filename)
        {
            $css .= `lessc $filename`;
            
            $output->writeln($filename);
        }
        
        file_put_contents($targetFilename, $css);
        
        $output->writeln('===== BUILDING JS =====');
        $targetFilename = path(__DIR__, '../Views/_resources/js/all.js');
        $javascript = '';
        
        foreach ($jsFiles as $filename)
        {
            $javascript .= file_get_contents($filename);
            
            $output->writeln($filename);
        }
        
        file_put_contents($targetFilename, $javascript);
        
        $output->writeln('===== UPDATING TIMESTAMP =====');
        $targetFilename = path(__DIR__, '../Views/_resources/timestamp.txt');
        
        file_put_contents($targetFilename, time());
        
        $output->writeln($targetFilename);
    }
    
}