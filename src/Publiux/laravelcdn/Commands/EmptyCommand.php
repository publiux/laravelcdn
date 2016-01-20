<?php

namespace Publiux\laravelcdn\Commands;

use Illuminate\Console\Command;
use Publiux\laravelcdn\Contracts\CdnInterface;

/**
 * Class PushCommand.
 *
 * @category Command
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 * @author   Raul Ruiz <publiux@gmail.com>
 */
class EmptyCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'cdn:empty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty all assets from CDN';

    /**
     * an instance of the main Cdn class.
     *
     * @var Vinelab\Cdn\Cdn
     */
    protected $cdn;

    /**
     * @param CdnInterface $cdn
     */
    public function __construct(CdnInterface $cdn)
    {
        $this->cdn = $cdn;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cdn->emptyBucket();
    }
}
