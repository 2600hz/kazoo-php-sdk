# Example

## Basics

CallflowBuilder adds simple methods to support creation and chaining of call flow nodes.  


To build a call flow, first create all the nodes to be used in the call flow. For now, the node builders require you to pass the entity ID for the node, so that the builder can attach the correct ID.
The entity can be obtained after creation by running $entity->getId();  

```php
<?php


//Use the callflow builders for each of the nodes you want to add for your callflow. 
use \CallflowBuilder\Node\User; 
use \CallflowBuilder\Node\Language;
use \CallflowBuilder\Node\Voicemail; 
use \CallflowBuilder\Node\Menu; 

//Use the callflow builder
use \CallflowBuilder\Builder;


/* 
    
Create a user, and voicemail entity and get the id. Or just get the id an existing entity from the sdk. 

*/

$user      = User($your_user->getId());
$voicemail = Voicemail($your_voicemail_box->getId());

//Attributes can be set on the individual entities
//For example, with users, you can set the value of canCallSelf to either true for false (default is FALSE). 
$user->canCallSelf(FALSE); 
//Also a timeout can be set on the user (default is 20). 
$user->timeout("100"); 


//Create a new instance of the builder class, this constructor takes either patterns or phone numbers as arguments
$flow_builder = new Builder( array(1234, 12345) );

/*
 You can use any node as the root element of your call flow, 
 The node you invoke first in the node builder chain will be the first element in the callflow.
  
*/
$user->addChild($language);
// subsequent calls to add child will add additional children to the end of the call flow. 
$user->addChild($voicemail); 

// These can be chained to build a simple call flow.
$user->addChild($language)->addChild($voicemail); 


//the removeChild method will remove the child of the object calling it, preserve rest of the chain. 
$language->removeChild(); 

//the removeChildren method will remove all the children below the object calling it.
$user->removeChildren(); 

//Once the flow is completed, you can build it by invoking the builder flow() method, passing the root object you used to build your flow nodes.
$builder->flow($user);







```
