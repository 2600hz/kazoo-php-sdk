# Example

## CallFlowBuilder

CallflowBuilder adds simple methods to support creation and chaining of complex callflow nodes. 

To build a call flow, first create all the nodes to be used in the call flow, then create the complete callflow with the Builder.

Import the callflow node builder namespaces

```php
//Use the callflow builders for each of the nodes you want to add for your callflow. 
use \CallflowBuilder\Node\User; 
use \CallflowBuilder\Node\Device;
use \CallflowBuilder\Node\Voicemail; 
use \CallflowBuilder\Node\Menu; 
use \CallflowBuilder\Node\Language;
use \CallflowBuilder\Node\PlayMedia; 
use \CallflowBuilder\Node\RingGroup; 
use \CallflowBuilder\Node\Callflow; 

``` 

Import the callflow builder

```php
use \CallflowBuilder\Builder;


```

The node builders require you to pass the entity ID for the node, so that the builder can attach the correct ID. The entity ID can be obtained after creation by running $entity->getId(); Using the User ID, create a user callflow node

```php
$user_id   = $your_user->getId()
$user_node = User();

```
Using the Voicemail box ID to create a callflow

```php
$voicemail_box_id = $your_voicemail_box->getId();
$voicemail_node   = Voicemail($voicemail_box_id);

```

Any node can be used as the root element of the call flow. The node invoked first in the node builder chain will be the first element in the callflow, the child element is returned after each addChild, so the calls can be chained to build a call flow. 
```php
$user->addChild($language);

```

Subsequent calls to add child will add additional children to the end of the call flow. 

```php
$user->addChild($voicemail); 

```

Since addChild returns the child object, these can be chained to build a simple call flow.

```php
$user->addChild($language)->addChild($voicemail); 

```

To construct the final callflow, create a new instance of the builder class using either an array of patterns or an array of phone numbers.

```php
$phone_numbers = array(1234, 5405551234);
$flow_builder  = new Builder( $phone_numbers );

```

Build the callflow by invoking the builder build() method, passing the root object used to build the callflow nodes.

```php
$builder->build($user);

```

The remove method will remove all the object calling it from the call flow.

```php
$voicemail->remove(); 

```

Children can be removed from call flows as well by calling the parent object's removeChild() method. The removeChild method will remove the child of the object calling it, preserving rest of the chain by collapsing to remove the child object.  

```php
$language->removeChild(); 

```

The removeChildren method will remove all the children below the object calling it.

```php
$user->removeChildren(); 

```

Once the flow is completed, it can be built by invoking the builder flow() method, passing the root object used to build the callflow nodes.

```php
$builder->flow($user);

```

#Setting Attributes on callflow nodes

Attributes can be set on the individual entities depending on type. For example, the user node's canCallSelf value can be set by calling the user node object's canCallSelf method with the argument TRUE or FALSE. 

```php
$user->canCallSelf(FALSE); 

```

###Callflow nodes

## Users and devices: 

Users and devices only require a user ID to add. Only two optional configurations are supported. 

canCallSelf - Which determines the users ability to call their own extension via this callflow (Default: FALSE).  
timeout -  which sets the amount of time the user will ring before the next call flow is chosen (Default: 20 seconds).

```php
   $user = new User("1232321312");
   $user->canCallSelf(TRUE); 
   $user->timeout(10); 
 
```

## Voicemail

Voicemail requires the mailbox ID of an existing voicemail box to be created. It has one configuration method **action()** which configures the type of action to be taken on the voicemail box. 
    
    action() - either compose or check (Default: compose).   

```php
   $voicemail_box_id = $your_voicemail_box->getId();
   $voicemail_node   = Voicemail($voicemail_box_id);
  
```

## Menu 

Menu requires a name to create. 

```php
   $menu   = Menu("MenuName");
  
```

To add options to the menu, use Menu's addChild method to add the next call flow node, specifying either no second argument for default or the menu number for the option.

```php
$menu->addChild($option_1, 1);
$menu->addChild($option_1, 2);
$menu->addChild($option_default);

```

## Language

language requires an existing language identifier (example: en_us) to be created. 

```php
   $language = Language("en_us");
  
```

## PlayMedia 

Play media requires a media ID of an existing media file to be created. 

```php
   $media = PlayMedia("12146546546546");
  
```

## Callflow 

Callflow requires the ID of an existing call flow to be created. 

```php
   $callflow = Callflow("12146546546546");
  
```

## Ring Groups

Ring groups require an array of IDs pointed to an array of options. 
The type option is required but the delay and timeout will use defaults if not set. 

#timeout

The time to ring the lines in the ring group before moving to the next call flow action (Default 20).

#strategy 

Can be set to single or simultaneous, determiens if all lines should ring simultaneously or individually 

The default is **simultaneous**. 

#endpoints 

The lists of entity ID (user or device) to ring in the group, and options that can be set on the endpoints. 
 
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
            "4534534534" => array(
               "type" => "user",
            ),
            "381028309" => array(
               "type" => "device",
               "delay" => "10"
            )   
         )   
    );        
