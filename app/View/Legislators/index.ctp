
<div class="list-group" id="my_legislators" >

</div>

<script>
$(document).ready(function()
{
	
	function location_accepted(position)
	{
		console.log(position);
		console.log('<?php echo Router::Url(array('controller' => 'Legislators', 'action' => 'geo_lookup'), TRUE); ?>'
			+'?'+$.param({lat: position.coords.latitude, 'long': position.coords.longitude}));
		$.ajax({
			type:'POST',
			async: true,
			cache: false,
			dataType: 'json',
			url: '<?php echo Router::Url(array('controller' => 'Legislators', 'action' => 'geo_lookup'), TRUE); ?>',
			success: function(response) {
				console.log(response);
				var content = '';
				$.each(response, function(index, value)
				{
					console.log(value);
					var photo_url = value.photo_url;
					
					if(photo_url.indexOf('ballotpedia.org') != -1)
					{
						photo_url = "/images/icons/empty_profile_picture.png"
					}
					
					content += //'<li class="list-group-item" >'
						'<a class="list-group-item" href="/legislators/view/'+value.id+'" >'
						+'<h3 class = "list-group-item-heading" >'+value.full_name+'</h3>'
						+'<div class="list-group-item-text row" >'
							+'<div class="col-xs-4" ><img src="'+photo_url+'" '
								+'onError="this.src = \'/images/empty_profile_picture.png\';" style="max-width: 128px;" >'
							+'</div>'
							
							+'<div class="col-xs-8" >';
							
							
							+'<p class="" >'+value.party+'</p>';
							
							$.each(value.offices, function(office_index, office)
							{
								if(office.phone == null)
									office.phone = '';
								if(office.email == null)
									office.email = '';
								content += '<address class="" >'
										+'<b>'+office.name+':</b><br/>'
										//+'<a href="https://maps.google.com?'+encodeURIComponent(office.address)+'" >'+office.address+'</a><br/>'
										+office.address+'<br/>'
										+office.phone+' '+office.email
									+'</address>';
							});
						
					content += '</div></div></div></a>';
						//+'<a href="http://votesmart.org/candidate/'+value.votesmart_id+'" target="_blank" ></a>'
					//+'<li>';
					//console.log(content);
				});
				$('#my_legislators').append(content);
			},
			error: function(response) {
				console.log(arguments);
				setTimeout(function()
				{
					//location_accepted(position);
					
				}, 2000);
			},
			data: {lat: position.coords.latitude, 'long': position.coords.longitude}
		});
	}
	
	
	function location_declined(error) 
	{
		$.mobile.loading( 'hide');
		
		console.log(JSON.stringify(error));
		
		if(error.code != 3)
			alert(gps_disabled_message);
	}
	
	
	
	
	
	/*
	getAccurateCurrentPosition(location_accepted, location_declined, 
		{
			enableHighAccuracy:true, 
			maximumAge:1000, 
			timeout:10000, 
			desiredAccuracy: 10000
		}
	);
	*/
	
	
	navigator.geolocation.getCurrentPosition(location_accepted, location_declined, {enableHighAccuracy:true, maximumAge:1000, timeout:10000});
	
	
});



</script>
