<?php

namespace Kazoo\Api\Entity;

/*
TODO: Add support for accounts/{account_id}/search
*/

class Search extends AbstractEntity {
    public function search($type, $value, $view)
    {
	$type		= urlencode($type);
	$value		= urlencode($value);
	$view		= urlencode($view);
	$response	= $this->get(array(), "/search?t=$type&q=$view&v=$value");
	
	return $response->getData();
    }
    
    public function searchAccounts($value, $view)
    {
	    return $this->search("account", $value, $view);
    }
    
    public function getAccountByRealm($realm)
    {
	    $result = $this->searchAccounts($realm, "realm");
	    
	    foreach ($result as $account)
	    {
		    if ($account->realm == $realm)
		    {
			    return $account;
		    }
	    }
	    
	    return FALSE;
    }
    
    public function getAccountByName($name, $equal = FALSE)
    {
	    $result = $this->searchAccounts($name, "name");
	    
	    if (is_array($result))
	    {
		    if ($equal)
		    {
			foreach ($result as $account)
			{
			    if ($account->name === $name)
			    {
				    return $account;
			    }
			}
			
			return FALSE;
		    }
		    
		    return $result;
	    }
	    
	    return FALSE;
    }
    
    public function getAccountById($id)
    {
	    $result = $this->searchAccounts($id, "id");
	    
	    return is_array($result) && count($result) ? $result[0] : FALSE;
    }

    protected function getUriSnippet()
    {
        return "/search";
    }
	
}
