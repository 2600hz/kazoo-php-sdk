# CallFlowBuilder


CallflowBuilder adds simple methods to support creation and chaining of complex callflow nodes. 

To build a call flow, first create all the nodes to be used in the call flow, then create the complete callflow with the Builder.

###Import the callflow node builder namespaces

```php
use \CallflowBuilder\Node\User; 
use \CallflowBuilder\Node\Device;
use \CallflowBuilder\Node\Voicemail; 
use \CallflowBuilder\Node\Menu; 
use \CallflowBuilder\Node\Language;
use \CallflowBuilder\Node\PlayMedia; 
use \CallflowBuilder\Node\RingGroup; 
use \CallflowBuilder\Node\Callflow; 

``` 

###Import the callflow builder

```php
use \CallflowBuilder\Builder;


```

The node builders require you to pass the entity ID for the node, so that the builder can attach the correct ID. The entity ID can be obtained after creation by running $entity->getId(); 

###Using the User ID, create a user callflow node

```php
$user_id   = $your_user->getId()
$user_node = User();

```
###Using the Voicemail box ID to create a callflow node

```php
$voicemail_box_id = $your_voicemail_box->getId();
$voicemail_node   = Voicemail($voicemail_box_id);

```

###Chaining call flow nodes

Any node can be used as the root element of the call flow. The node invoked first in the node builder chain will be the root element in the callflow.  

```php
$user_node->addChild($voicemail_node);

```
The child element is returned after each addChild, so the calls can be chained to build a call flow

```php
$user_node->addChild($language_node)->addChild($voicemail_node);

```

Subsequent calls to addLastChild will add additional children to the end of the call flow. 

```php
$user_node->addChild($media_node);
$user_node->addLastChild($language_node); 
$user_node->addLastChild($voicemail_node); 

```


###Building Callflows
To construct the callflow, create a new instance of the builder class using either an array of patterns or an array of phone numbers.

```php
$phone_numbers = array(1234, 5405551234);
$flow_builder  = new Builder( $phone_numbers );

```

Build the callflow by invoking the builder build() method, passing the root object used to build the callflow nodes.

```php
$builder->build($user_node);

```

###Removing children

Children can be removed from call flows as well by calling the parent object's removeChild() method. The removeChild method will remove the child of the object calling it, preserving rest of the chain by collapsing to remove the child object.  

```php
$language_node->removeChild(); 

```

The removeChildren method will remove all the children below the object calling it.

```php
$user_node->removeChildren(); 

```

##Setting Attributes on callflow nodes

Attributes can be set on the individual entities depending on type. For example, the user node's canCallSelf value can be set by calling the user node object's canCallSelf method with the argument TRUE or FALSE. 

```php
$user_node->canCallSelf(FALSE); 

```

#Callflow nodes


## Users and Devices

Users and devices only require a user ID to add. Only two optional configurations are supported. 

###canCallSelf 
Which determines the users ability to call their own extension via this callflow 

The default is **FALSE**
  
###timeout 

Sets the amount of time the user will ring before the next call flow is chosen. 

The default is **20** seconds.

####Example

```php
   $user_node = new User("1232321312");
   $user_node->canCallSelf(TRUE); 
   $user_node->timeout(10); 
 
```

## Voicemail

Voicemail requires the mailbox ID of an existing voicemail box to be created. It has one configuration method **action()** which configures the type of action to be taken on the voicemail box. 
    
### action

Options are **compose** or **check**. 

The default is **compose**.   

```php
   $voicemail_box_id = $your_voicemail_box->getId();
   $voicemail_node   = Voicemail($voicemail_box_id);
  
```

## Menu 

Menu requires a name to create. 

```php
   $menu_node   = Menu("MenuName");
  
```

To add options to the menu, use Menu's addChild method to add the next call flow node, specifying either no second argument for default or the menu number for the option.

```php
$menu_node->addChild($option_1, 1);
$menu_node->addChild($option_1, 2);
$menu_node->addChild($option_default);

```

## Language

language requires an existing language identifier (example: en_us) to be created. 

```php
   $language_node = Language("en_us");
  
```

## PlayMedia 

Play media requires a media ID of an existing media file to be created. 

```php
   $media_node = PlayMedia("12146546546546");
  
```

## Callflow 

Callflow requires the ID of an existing call flow to be created. 

```php
   $callflow_node = Callflow("12146546546546");
  
```

## Temporal route

Temporal routes take timezones as arguments. Add the account ID as the index for temporal routes children and default for "all other times".  


```php
   $temporal_route_node = TemporalRoute($timezone); 
   $temporal_route_node->addChild($user_node, $temporal_route_id); // timeframe specified 
   $temporal_route_node->addChild($user2_node); // all other times

```

if an action and ruleset is specified, this option can be used to enable, disable or reset time of day routing. 

```php
 
    $rules = array($time_rule1, $time_rule2); 
    
    //reset time of day option
    $temporal_route_node = TemporalRoute(); 
    $temporal_route_node->action("reset"); 
    $temporal_route_node->rules($rules);
  
    //enable time of day options
    $temporal_route_node = TemporalRoute(); 
    $temporal_route_node->action("enable"); 
    $temporal_route_node->rules($rules);

    //disable time of day routing 
    $temporal_route_node = TemporalRoute(); 
    $temporal_route_node->action("disable"); 
    $temporal_route_node->rules($rules);
  
 
```

##Resource/Offnet

Carrier resources can be added to a call flow to allow access to either the accounts carriers, a parent accounts carriers or the global offnet resource. The typical call flow for this is to use numbers = array("no_match") and specify the only call flow node as either an account resource or an offnet resource. 


```php
    $offnet_resource_node  = Offnet();
    $account_resource_node = Resource($account_id);     
```

##Pivot

Pivot allows calls to external HTTP servers from call flows. This exposes real time call control that can be managed via an external server. 

The pivot module requires four options to configure. 

###method
   The method used for the http request; 
###req_timeout
   The timeout in seconds before an http request is dropped and the next call flow node is selected. 
###req_format
   The data payload format to use. 
   The optiosn are **kazoo** or **twixml**.
###voice_url
   The url to send the http request. 


```php
    $pivot_node = Pivot();
    $pivot_node
        ->method("POST")
        ->req_timeout("19")
        ->req_format("kazoo")
        ->voice_url("https://your.pivotserver.com:8000");

```

## Page Group

Page groups require an array of epoints containing an associative array of IDs which point to the type of resource. 

```php
     $page_group_node = PageGroup("page group name");
     $page_group_node->endpoints(
          array (
             "23948203984" => "user",
             "42874298374" => "device"
          )
     );  


```

## Ring Group

Ring groups require an array of IDs pointed to an array of options. 
The type option is required but the delay and timeout will use defaults if not set. 

###timeout

The time in seconds to ring the lines in the ring group before moving to the next call flow action 

The default is **20** seconds.

###strategy 

Can be set to single or simultaneous, determiens if all lines should ring simultaneously or individually 

The default is **simultaneous**. 

###endpoints 

The lists of entity ID (user or device) to ring in the group, and options that can be set on the endpoints. 
 
**ID** 
 The id of the device or user which is an endpoint in the ring group.   
 This value is **required**.
     
**type**
The id the ID of a user or device. 
This value is **required**.
     
**timeeout** 
How long each entity should ring before timeout.
The default is **20** seconds.

**delay** 
How long to wait before ringing the line. 
The default is **0** seconds. 

###Example
```php
    $ring_group_node = new RingGroup("MY_RING_GROUP");                                                                            
   
    $ring_group_node->timeout("10");
    $ring_group_node->strategy("single"); 
    $ring_group_node->endpoints( 
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
