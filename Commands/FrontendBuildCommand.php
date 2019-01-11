<?php

namespace ProVallo\Plugins\Frontend\Commands;

use ProVallo\Components\Command;
use ProVallo\Core;
use ProVallo\Plugins\Frontend\Models\Domain\Domain;
use ProVallo\Plugins\Frontend\Models\Theme\Theme;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FrontendBuildCommand extends Command
{
    
    /**
     * @var \ProVallo\Plugins\Frontend\Components\Themes\Themes
     */
    protected $themeService;
    
    protected function configure ()
    {
        $this->setName('frontend:build');
        $this->setDescription('Builds JS and LESS for the frontend.');
        
        $this->addOption('domainID', null, InputOption::VALUE_REQUIRED, 'Build theme files for specific domain.');
    }
    
    protected function initialize (InputInterface $input, OutputInterface $output)
    {
        $this->themeService = Core::di()->get('frontend.themes');
    }
    
    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $domainID = (int) $input->getOption('domainID');
        
        if ($domainID > 0)
        {
            $domain = Domain::repository()->find($domainID);
            
            if ($domain instanceof Domain)
            {
                $this->buildThemeFiles($domain, $input, $output);
            }
            else
            {
                $output->writeln('ERROR: The given domainID is not found.');
            }
        }
        else
        {
            $domains = Domain::repository()->findAll();
            
            foreach ($domains as $domain)
            {
                $this->buildThemeFiles($domain, $input, $output);
            }
        }
    }
    
    protected function buildThemeFiles (Domain $domain, InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Processing "' . $domain->label . '"');
        
        /** @var Theme $theme */
        $theme = $domain->theme;
        
        if ($theme instanceof Theme)
        {
            $this->themeService->getCompiler()->compile($domain->theme, $domain);
        }
        else
        {
            $output->writeln('  The current domain has no theme.');
        }
    }
    
}