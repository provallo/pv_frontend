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
     * @var \ProVallo\Plugins\Frontend\Components\Themes
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
                $this->buildThemeFiles($domain);
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
        
        $theme = $domain->theme;
        
        if ($theme instanceof Theme)
        {
            $themeDir    = path($this->themeService->getThemeDirectory(), $theme->name);
            $resourceDir = path($themeDir, '_resources');
            
            if (is_dir($themeDir))
            {
                $lessFiles = Core::events()->collect('frontend.register.less', compact('domain', 'theme'));
                $jsFiles   = Core::events()->collect('frontend.register.javascript', compact('domain', 'theme'));
                
                $cssFilename  = path($resourceDir, 'dist', sprintf('all_%d.css', $domain->id));
                $jsFilename   = path($resourceDir, 'dist', sprintf('all_%d.js', $domain->id));
                $timeFilename = path($resourceDir, 'dist/timestamp.txt');
                
                // Compile multiple LESS into CSS file
                $css = '';
                
                foreach ($lessFiles as $filename)
                {
                    $css .= `lessc $filename`;
                }
                
                $javascript = '';
                
                foreach ($jsFiles as $filename)
                {
                    $javascript .= file_get_contents($filename);
                }
                
                $this->writeFile($cssFilename, $css);
                $this->writeFile($jsFilename, $javascript);
                $this->writeFile($timeFilename, time());
            }
            else
            {
                $output->writeln('  The theme directory does not exists');
                $output->writeln('  ' . $themeDir);
            }
        }
        else
        {
            $output->writeln('  The current domain has no theme.');
        }
    }
    
    protected function writeFile ($filename, $content)
    {
        $this->ensureDirectory($filename);
        
        file_put_contents($filename, $content);
    }
    
    protected function ensureDirectory ($filename)
    {
        $directory = pathinfo($filename, PATHINFO_DIRNAME);
        
        if (!file_exists($directory))
        {
            mkdir($directory, 0777, true);
        }
    }
    
}