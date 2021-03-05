<?php

namespace Meteko\PolicyAnnotation\Configuration;

use Meteko\PolicyAnnotation\Annotations\Policy;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Reflection\ReflectionService;
use Neos\Flow\Security\Authorization\Privilege\Method\MethodPrivilege;

/**
 * @Flow\Scope("singleton")
 */
class PolicyConfiguration {

    /**
     * @Flow\Inject
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $map;

    public function initializeObject() {
        $this->map = self::resolveAnnotatedMethods($this->objectManager);
    }

    public function configure(array &$policyConfiguration) {
        foreach ($this->map as $identifier=>$configuration) {
            $policyConfiguration['privilegeTargets'][MethodPrivilege::class][$identifier]['matcher'] = $configuration['matcher'];

            foreach ($configuration['roles'] as $role) {
                $policyConfiguration['roles'][$role['identifier']]['privileges'][] = [
                    'privilegeTarget' => $identifier,
                    'permission' => $role['permission']
                ];
            }
        }
    }

    /**
     * @param ObjectManagerInterface $objectManager
     * @Flow\CompileStatic
     */
    protected static function resolveAnnotatedMethods(ObjectManagerInterface $objectManager)
    {
        $privileges = [];
        $reflectionService = $objectManager->get(ReflectionService::class);
        $classesWithMethodsAnnotated = $reflectionService->getClassesContainingMethodsAnnotatedWith(Policy::class);
        foreach ($classesWithMethodsAnnotated as $className) {
            $methodsAnnotated = $reflectionService->getMethodsAnnotatedWith($className, Policy::class);
            foreach ($methodsAnnotated as $methodName) {
                $annotations = $reflectionService->getMethodAnnotations($className, $methodName, Policy::class);
                $matcher = sprintf('method(%s->%s())', $className, $methodName);
                $matcherIdentifier = sha1($matcher);
                $roles = array_map(function ($annotation) {
                    return [
                        'identifier' => $annotation->role,
                        'permission' => $annotation->permission,
                    ];
                }, $annotations);
                $privileges[$matcherIdentifier] = [
                    'matcher' => $matcher,
                    'roles' => $roles
                ];
            }
        }

        return $privileges;
    }


}