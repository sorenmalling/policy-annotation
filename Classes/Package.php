<?php
namespace Meteko\PolicyAnnotation;

use Meteko\PolicyAnnotation\Configuration\PolicyConfiguration;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\Security\Authentication\Token\SessionlessTokenInterface;
use Neos\Flow\Security\Authentication\TokenInterface;
use Neos\Flow\Security\Policy\PolicyService;
use Neos\Flow\Session\SessionInterface;


/**
 * The Flow Package
 */
class Package extends BasePackage
{

    /**
     * Invokes custom PHP code directly after the package manager has been initialized.
     *
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(PolicyService::class, 'configurationLoaded', PolicyConfiguration::class, 'configure');

    }
}
