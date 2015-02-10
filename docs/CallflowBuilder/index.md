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


## Users and devices: 

Users and devices only require a user ID to add. Only two optional configurations are supported. 

canCallSelf - Which determines the users ability to call their own extension via this callflow (Default: FALSE).  
timeout -  which sets the amount of time the user will ring before the next call flow is chosen (Default: 20 seconds).
 

```php

   $user = new User("1232321312");
   $user->canCallSelf(TRUE); 
   $user->timeout(10); 


```


## Ring Groups

Ring groups require an array of IDs pointed to an array of options. 
The type option is required but the delay and timeout will use defaults if not set. 

timeout - The time to ring the lines in the ring group before moving to the next call flow action (Default 20).
strategy - single or simultanious, determiens if all lines should ring simultaniously or individually (Default: simultanious). 
endpoints - the lists of entity ID (user or device) to ring in the group, and options that can be set on the endpoitns: 
     id - the id of the device or user which is an endpoint in the ring group.   
     type - (required) either device or user can be set here. 
     timeeout - how long each entity should ring before timeout.
     delay - how long to wait before ringing the line. 
 

```php

    $ring_group = new RingGroup("MY_RING_GROUP");                                                                                                                           

    $ring_group->timeout("10");
    $ring_group->strategy("single"); 
    $ring_group->endpoints( 
        array (
             "23948203984" => array(
                   "type" => "user",
                   "timeout" => "10"
              ),  
              "381028309" => array(
                   "type" => "device",
                   "delay" => "10"
              )   
         )   
    );        

``

`
