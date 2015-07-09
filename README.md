Rcm Api Lib
====================

API basic response library making use of ZF2's regular MVC framework

##### Module title: #####
Rcm Api Lib

##### Module description: #####
Rcm Api Library

##### Company:
Reliv' International, Inc.

##### Module copyright date: #####
2015

##### Company or root namespace: #####
Reliv
 
##### Module namespace: #####
RcmApiLib

##### Project root name, lowercase, dash separated: #####
reliv

##### Project name, lowercase, dash separated: #####
rcm-api-lib

##### Project homepage: #####
https://github.com/reliv/rcm-api-lib

##### Project author: #####
James Jervis

##### Project author email: #####
jjervis@relivinc.com

##### Project author homepage: #####
https://github.com/reliv


##### EXAMPLE #####
```php

// From a Controller that extends \Reliv\RcmApiLib\Controller\AbstractRestfulJsonController

    // Add exception message
    $this->addApiMessage(
        new \Exception('Some exception')
    );

    // Add generic message as array
    $this->addApiMessage(
        [
            'key' => 'ArrayError',
            'value' => 'Some {param} Message',
            'primary' => true,
            'type' => 'Array',
            'code' => 'mycode',
            'params' => ['param' => 'array message']
        ]
    );

    // Add generic message as object
    $this->addApiMessage(
        new ApiMessage('MYKEY', 'Some Message')
    );
    
    // Add HTTP sttus message
    $this->addApiMessage(
        new HttpStatusCodeApiMessage(403)
    );

    // Add inputFilter message
    $inputFilter = new \Zend\InputFilter\InputFilter(); // Use you own inputFilter here
    $this->addApiMessage(
        $inputFilter
    );
    
    // Return the response
    return $this->getApiResponse(
        null,
        $statusCode = 200,
        $inputFilter,
        true
    );
    
    // Return the response with your data and no messages
    return $this->getApiResponse(
        ['myThing' => 'someThing'],
    );
    
```
