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
use \CallflowBuilder\Node\Play; 
use \CallflowBuilder\Node\Callflow; 
use \CallflowBuilder\Node\Pivot; 
use \CallflowBuilder\Node\Resource; 
use \CallflowBuilder\Node\TemporalRoute; 
use \CallflowBuilder\Node\PageGroup; 
use \CallflowBuilder\Node\RingGroup; 
use \CallflowBuilder\Node\CallForward;
use \CallflowBuilder\Node\Hotdesk;
use \CallflowBuilder\Node\Intercom;
use \CallflowBuilder\Node\Park;
use \CallflowBuilder\Node\Privacy;

``` 

###Import the callflow builder

```php
use \CallflowBuilder\Builder;

```

The node builders require you to pass the entity ID for the node, so that the builder can attach the correct ID. The entity ID can be obtained after creation by running $entity->getId(); 

###Using the User ID, create a user callflow node

```php
$user_id   = $your_user->getId();
$user_node = new User($user_id);

```
###Using the Voicemail box ID to create a callflow node

```php
$voicemail_box_id = $your_voicemail_box->getId();
$voicemail_node   = new Voicemail($voicemail_box_id);

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

addChild() supports an index as a second argument. This is useful for adding menu options. By default the index '_' is used, but can be overridden by specifying a second argument in add child. 

```php
//sets option one for the menu to $user_node.
$menu_node->addChild($user_node, 1);
//sets option two for the menu to $other_user_node
$menu_node->addChild($other_user_node, 2);
//sets the default option in the menu to $voicemail_node
$menu_node->addChild($voicemail_node); 

```

###Building Callflows
To construct the callflow, build the callflow by invoking the builder build() method, passing the root object used to build the callflow nodes. A full data payload will be constructed which can be assigned to the SDK callflow object via the SDK callflow entitie method fromBuilder(). 

```php
$your_call_flow = $sdk->Account()->Callflow(); 
$phone_numbers  = array(1234, 5405551234);

$builder = new Builder($phone_numbers); 
$data = $builder->build($user_node);
$your_call_flow->fromBuilder($data);
$your_call_flow->save(); 

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
For menus and temporal routes, the removeChild and removeChildren() function supports removal using an optional index argument. 

```php
//remove the first child of the default option from menu. 
$menu_node->removeChild('_');
//remove all the children at option 2 in a menu.
$menu_node->removeChildren('2');
//remove the temporal route for index $time_id 
$temporal_route_node->removeChild($time_id); 

```

##Setting Attributes on callflow nodes

Attributes can be set on the individual node elements depending on the attributes supported by the type of element. For example, the User node's canCallSelf value can be set by calling the User node object's canCallSelf method with the argument TRUE or FALSE. 

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
   $user_id   = $your_user->getId();
   $user_node = new User($user_id);
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
## Conference 

Conference requires an ID of an existing conference to create the node. 

```php
   $conference_id     = $sdk_conference->getId(); 
   $conference_node   = Conference($conference_id);
  
```

Conference Service does NOT require an ID of existince conference.

```php
   $conference_node   = Conference();
  
```

## Menu 

Menu requires an ID of an existing menu to create the node. 

```php
   $menu_id     = $sdk_menu->getId(); 
   $menu_node   = Menu($menu_id);
  
```
###Adding menu options
To add options to the menu, use Menu's addChild method to add the next call flow node, specifying either no second argument for default or the menu number for the option.

```php
$menu_node->addChild($option_1, 1);
$menu_node->addChild($option_1, 2);
$menu_node->addChild($option_default);

```

## Language

language requires an existing language identifier (example: en-us) to be created. 

```php
   $language_node = Language("en-us");
  
```

## Play 

Play (media) requires a media ID of an existing media file to be created. 

```php
   $media_id   = $sdk_media->getId();
   $media_node = Play($sdk_media);
  
```

## Callflow 

Callflow requires the ID of an existing call flow to be created. 

```php
   $callflow_id   = $sdk_callflow->getId();
   $callflow_node = Callflow($callflow_id);
  
```

## Temporal route

Temporal routes take timezones as arguments. Add the account ID as the index for temporal routes children and default for "all other times".  


```php
   $temporal_route_node = TemporalRoute("America/Los_Angeles"); 
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

##Resources

Carrier resources can be added to a call flow to allow access to either the accounts carriers, a parent accounts carriers or the global offnet resource. The typical call flow for this is to use numbers = array("no_match") and specify the only call flow node as either an account resource or an offnet resource. 

By default the Resources node will be built as offnet if no account_id is specified in the constructor. If an account ID is not specified, the value of use_local_resource is set to FALSE. If the account ID is specified in the constructor, the value use_local_resource is left as default (TRUE) and the account number is assigned to the hunt_account_id value. These parameters can be individually accessed as well via the exposed methods useLocalReesource and HuntAccountId

```php
    $offnet_resource_node  = new Resources();
    $account_resource_node = new Resource($account_id);  
       
```

the methods supported by resources are

###to_did()

Directs a call to the specified DID

###media()

Plays a media file prior to connecting the call to resources. 
Takes a media ID. 

###ringback()

Plays a custom ringback while connecting calls to resources. 
Takes a ringback ID. 

###formatFromDid()

Accepts TRUE or FALSE

###timeout()

Timeout in seconds. 

####doNotNormalize()

Accepts TRUE or FALSE

####bypassE164()

Accepts TRUE or FALSE

####fromUriRealm()

Sets the from URI realm to the string specified. 

####callerIdType()

Sets the caller ID type to what is specified  

####useLocalResources()

Accepts TRUE or FALSE

####huntAccountId()

Accepts an account ID. The specified accounts carrier resources will be used for offnet calling. 

####emitAccountId()

Puts the specified account id in SIP header. 

####customSipHeaders()

An associative array of custom sip headers.

####ignoreEarlyMedia() 

Accepts TRUE or FALSE.

####outboundFlags()

Accepts an array of flags. 

##Pivot

Pivot allows calls to external HTTP servers from call flows. This exposes real time call control that can be managed via an external server. 

The pivot module requires four options to configure. 

###method

The method used for the http request.

###req_timeout

The timeout in seconds before an http request is dropped and the next call flow node is selected. 

###req_format

The data payload format to use. 
The optiosn are **kazoo** or **twixml**.

###voice_url

The url to send the http request. 


```php
    $pivot_node = new Pivot();
    $pivot_node
        ->method("POST")
        ->req_timeout("19")
        ->req_format("kazoo")
        ->voice_url("https://your.pivotserver.com:8000");

```

## PageGroup

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

## RingGroup

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
```

# Feature Codes 

Feature codes are single element call flows. The feature codes support multiple actions which can be set useingthe feature codes action() method. Each action determines which specific sub-feature will be invoked when the feature code is dialed. NOTE: Features which require a feature code follewed by a digit pattern should use **pattern** instead of number when creating the builder.

IE: $builder = new Builder(null, $pattern);

##Call Forwarding

Call forwarding has multiple actions which can be mapped to feature codes using the action method in the node builder CallFowarding.php. 

###action()

The actions that can be set are 

####activate
This activates call forwarding on the line invoking the feature code. 

Generally this is mapped to **\*72**

####deactivate
This deactivates call forwarding on the line invoking the feature. 

Generally this is mapped to **\*73**

####update
This updates the fowarding number to a new number on the line invoking the feature. 


####toggle
This either activates or deactivates forwarding based on what is currently set. Patterns should be used instead of numbers for this feature code to work properly.  

This is generally mapped to **\*74{pattern}**.

Example: ``` "^\\*74([0-9]*)$" ```

####menu
This provides a menu that can be used to configure call fowarding. 

Generally this is mapped to **\*56** 


##Hotdesk

###action()
   
The action method determines which action the feature code will invoke. 

####login

Enable enables the hotdesking feature on the line invoking it, for the extension specified. 

This is generally mapped to **\*11**.

####logout

Disable hotdesking on the line invoking it. 

This is generally mapped to **\*12**.

####toggle

Toggles between login and logout on the line invoking it. 

This is generally mapped to **\*13**.

####bridge

This does... something? Apperently no one knows what this does, if you find out please tell me so I can know too. 

This is generally not exposed but is available. 

##Intercom

Intercom feature has no configuration methods. This uses a pattern instead of a number so that the additional digits (the intercom destination extension) can be added to the feature code pattern. 

This is generally mapped to **\*0{pattern}**.

Example: ``` "^\\*0([0-9]*)$" ```

##Privacy

Privacy activates caller ID blocking on an outbound call. Since the feature is intended to be used by entering the feature code followed by the destination number, this should use a **pattern** instead of a number.  

This is generally mapped to **\*67{pattern}**.

Example: ``` "^\\*67([0-9]*)$" ```

###mode()

this should be set to **full**  


##Park

Parking feature had an action method which determines the behavior when invoked. 

###action()

The available actions are 

####auto

If the call is parked, it will retrieve it, if the call is active, it will park it. This feature uses **patterns** instead of numbers since it is intended to be used to park and retrieve calls from a parking lot input by the user parking the call. 

This is generally mapped to **\*3{pattern}**.

Example: ``` "^\\*3([0-9]*)$" ```

####park
Action 'park' (aka Valet) will park the call in the next available parking lot number. 

This is generally mapped to **\*4**

####retrieve
retrieves a parked call using the parking lot number specified. This feature uses **pattern** instead of number since it is intended to be used to retrieve a parked call from a specific lot.  

This is generally mapped to **\*5{pattern}**.

Example: ``` "^\\*5([0-9]*)$" ```

#Voicemail

Using the voicemail class you can define two feature codes, direct to voicemail and check voicemail. These are set via the action() method. These features should NOT have assigned voicemail box as they are intended to be used by all the users on the account using the feature code. 

##action() 

###check
This is a feature that can be invoked by the user to check voicemail.

This is generally mapped to **\*97**

###compose
This feature is used to direct a call to another users voicemail. Since invocation of the feature requires the feature code and then an extension, this should use a **pattern** instead of a number. 


This is generally mapped to **\*\*{pattern}**.

Example: ``` "^\\*\\*([0-9]*)$" ```

        
