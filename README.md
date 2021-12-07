## Keycloack-token-bundle

## Requirements
What things you need to install the software : 

>PHP 7.2.5 or higher;

### Getting Started
Run these commands:

```
composer require amiltone/keycloack-token-bundle
```

## Keycloack User Token verification for symfony

Verification of the Bearer token located in header Authorization on:
- Annotation routes
- YAML routes
 
## Examples

### Annotation usage

*routes.yaml*

```
index:
    path: /home
    controller: App\Controller\HomeController::index
```
*HomeController.php*
```
<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\UserVerification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @UserVerification
     */
    public function index(Request $request): Response
    {
        $user = $request->get("user");
        return new Response("ok");
    }
}
```
### Yaml usage

*routes.yaml*
```
indexByYaml:
    path: /homeYaml
    controller: App\Controller\HomeController::indexYaml
    defaults: { userVerification: true}
```

*HomeController.php*
```
<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\UserVerification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends AbstractController
{
    public function indexYaml(Request $request): Response
    { 
        $user = $request->get("user");
        return new Response("ok");
    }
}

```