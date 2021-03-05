# Policy configuration from method annotation

The package provides a way to annotate methods with a `@Policy` annotation and set permission for a role.

The format looks like this (taken from the `TestController` you can find in this package)

```
<?php

namespace Meteko\PolicyAnnotation\Controller;

use Neos\Flow\Mvc\Controller\ActionController;
use Meteko\PolicyAnnotation\Annotations\Policy; #!!!This is the imported annotation

class TestController extends ActionController
{
    /**
     * @Policy(role="Meteko.PolicyAnnotation:TestRole", permission="grant")
     */
    public function indexAction()
    {

    }
}
```

The roles does not need to exist in your `Policy.yaml` file - it will automatically be added to the policy configuration

Using the `./flow security:listroles` commands output the roles as expected

```
+----------------------------------+----------+
| Id                               | Label    |
+----------------------------------+----------+
| Meteko.PolicyAnnotation:TestRole | TestRole |
+----------------------------------+----------+
```