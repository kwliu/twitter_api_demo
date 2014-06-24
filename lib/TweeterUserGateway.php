<?php

require_once('lib/TwitterAPIExchange.php');

/**
 * Wrapper for tweeter api returned objects
 *  
 * 
 * @author   Henry Liu
 * 
 */
class TweeterUserGateway
{
    private $twitter_api_exchange;


    /**
	 *
	 * Constructor of TweeterUserGateway, requires TwitterAPIExchange API wrapper ojbect as input 
	 *
	 *	 
     * @param TwitterAPIExchange
     *
     */
    public function __construct($twitter_api_exchange)
    {
        $this->twitter_api_exchange = $twitter_api_exchange;
    }
    
    
    /*
     * Return the most recent tweets from the twitter account
     *
     *
     *@param string $twitter_username Twitter username
     *@$max int number of recent tweets to be retieve, default is 15
     *
     *@return array tweets in ssociative array
     *
     */
    
    public function get_recent_tweets($twitter_username, $max = 15)
    {
		
		if(empty($twitter_username)) throw new ErrorException('Twitter account name is required');
		
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield ="?count=$max&screen_name=".urlencode($twitter_username);
        
        $response = array();
        
		$this->twitter_api_exchange->setGetfield($getfield);
		$this->twitter_api_exchange->buildOauth($url,'GET');
		$response = json_decode($this->twitter_api_exchange->performRequest(), true);

	
        
        if(is_null($response))
        {
            throw new ErrorException('Unable to to connect twiiter api server, please try again later');
        }elseif(key_exists('errors',$response))
        {
            throw new ErrorException('Twitter account "'.htmlentities($twitter_username).'" doesn\'t exists');
        }elseif(key_exists('error',$response))
		{
			throw new ErrorException($response['error']);
		}
        
        return $response;
        
    }
    
    /*
     * Return required twitter account profile
     *
     *@param string $twitter_username Twitter username
     *
     *@return array account profile in ssociative array
     *
     */	
    public function get_user_info($twitter_username)
    {
		
		if(empty($twitter_username)) throw new ErrorException('Twitter account name is required');
		
        $response = array();
        
        $url = 'https://api.twitter.com/1.1/users/show.json';
        $getfield ="?screen_name=".urlencode($twitter_username);
        
		$this->twitter_api_exchange->setGetfield($getfield);
		$this->twitter_api_exchange->buildOauth($url,'GET');
		$response = json_decode($this->twitter_api_exchange->performRequest(),true);
			
		
        if(is_null($response))
        {
            throw new ErrorException('Unable to to connect twiiter api server, please try again later');
        }elseif(key_exists('errors',$response))
        {
            throw new ErrorException('Twitter account "'.htmlentities($twitter_username).'" doesn\'t exists');
        }elseif(key_exists('error',$response))
		{
			throw new ErrorException($response['error']);
		}
        
        return $response;
        
    }    

}