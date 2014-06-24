<?php
	require_once('config.php');
	require_once('lib/TwitterAPIExchange.php');
	require_once('lib/TweeterUserGateway.php');
	
	$err_message = '';  // string for error messages
	$has_error = false; // check if there are any Exception throw during api calls
	$tweet_objs = array(); // array to store tweets info from the user
	$user_info = array(); // array to store tweeter account information
	
	$twitter_screen_name = isset($_POST['twiiter_name'])?trim($_POST['twiiter_name']):'';
	
	
	if(!empty($twitter_screen_name))
	{
	
		$settings = array(
			'oauth_access_token' => ACCESS_TOKEN,
			'oauth_access_token_secret' => ACCESS_SECRET,
			'consumer_key' => CONSUMER_KEY,
			'consumer_secret' => CONSUMER_SECRET
		);
		
		try{
			
			$TweeterUserGateway = new TweeterUserGateway(new TwitterAPIExchange($settings));
			
			$tweet_objs = $TweeterUserGateway->get_recent_tweets($twitter_screen_name,5);
			
			$user_info = $TweeterUserGateway->get_user_info($twitter_screen_name);

		}catch(ErrorException $e)
		{
			
			$has_error = true;
			$err_message = $e->getMessage();
		}
	}
?>

<style type="text/css">
body {
	font-family: verdana,helvetica,arial,sans-serif;
}
.display_name {
	display: inline-block;
	color: #FFFFFF;
	background-color: #8AC007;
	font-weight: bold;
	font-size: 12px;
	text-align: center;
	padding-left: 10px;
	padding-right: 10px;
	padding-top: 3px;
	padding-bottom: 4px;
	text-decoration: none;
	margin-left: 0;
	margin-top: 0px;
	margin-bottom: 5px;
	border: 1px solid #aaaaaa;
	border: 1px solid #8AC007;
	border-radius: 5px;
	white-space: nowrap;
	
}

table {
	border: solid;
	border-width:2px;
	border-collapse: collapse;
	width:100%
}

td {
	padding: 5px;
	border: solid;border-width:2px;
}
</style>

<html>
	<head>
		<meta charset="utf-8">
		<title>Twitter application demo</title>
	</head>
	<body>
		<div  style="margin: 5px auto;"><h2>Twitter PHP application demo</h2></div>
		<form action="index.php" method="post">
			Twitter account name: <input type="text" name="twiiter_name" required>
			<input type="submit">
		</form>
		<hr/>
		<?php if(!$has_error && !empty($twitter_screen_name)) {	?>
		<div style="margin: auto">
			<table style="">
				<tr>
					<td colspan="2">
						Twitter account: <b><a class="display_name" target="_blank" href="https://twitter.com/<?=$twitter_screen_name?>"><?=$twitter_screen_name?></a></b></br>
						Number of follower: <b><?=$user_info['statuses_count']?></b></br>
						Following: <b><?=$user_info['friends_count']?></b></br>
						Followers: <b><?=$user_info['followers_count']?></b></br>
					
					</td>
				</tr>
				<tr style="background-color: rgb(40, 186, 255)"><td colspan="2"><?=empty($tweet_objs)?'No tweets from this twitter account':('Last 5 tweets from this user')?></td></tr>
				<?php foreach($tweet_objs as $tweet_obj) {?>
				<tr><td style="width: 20%"><?=date("Y-m-d H:i:s", strtotime($tweet_obj['created_at']))?></td><td><?=html_entity_decode(($tweet_obj['text']))?></td></tr>
				<?php } ?>
			</table>
		<?php }elseif($has_error && !empty($err_message)) { ?>
			<h2><?=$err_message?></h2>
		<?php } ?>
		</div>
	</body>
</html>

