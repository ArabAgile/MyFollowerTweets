<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My followers feed</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css" media="screen">

    h1 {
    	color: #55acee;
    }
    	
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

  	<div class="container">
	    <h1>What my followers tweet?</h1>

	    <div class="col-sm-4">

	    	@foreach ($followers as $follower)
			<div class="media">
		    	<a class="pull-left" href="http://twitter.com/{{$follower->screen_name}}" target="_blank">
		    		<img class="media-object" src="{{$follower->photo}}" alt="{{$follower->name}}">
		    	</a>
		    	<div class="media-body">
		    		<h4 class="media-heading">{{$follower->name}}</h4>
		    		<p> @if (isset($follower->tweets, $follower->tweets['0']))
		    				{{$follower->tweets[0]->tweet}}
		    			@endif
		    		</p>

		    	</div>
		    </div>

		    @endforeach

	    </div>

    </div>


    <div class="clearfix"></div>

    <div>
    	{{ $followers->links(); }}
    </div>
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>