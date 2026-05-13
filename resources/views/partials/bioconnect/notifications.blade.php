<script type="text/javascript"> 
	notifications();

	setInterval(function(){ notifications(); }, 5000);
			
	$( "#see_notifi" ).click(function(ev) {
		ev.preventDefault();
		$('#notifi_desc').slideToggle();
	});
	
	$( "#notifi_desc" ).on('click', 'li',function(ev) {
		ev.preventDefault();
		
		var hitAction = $(this).attr('data-action');
		$('.'+hitAction).click();
	});

	function notifications() {
		var addnotifi = 0;
		var fbinitnotifi = firebase.database().ref().child('chats').orderByChild("chatto").equalTo("{{Auth::user()->id}}");

		var notificontent = '';
		fbinitnotifi.on("value", function (snap) {
			snap.forEach(function (childsnap) {
				var getData = childsnap.val();
				if(getData.msgView === '0'){
					notificontent += '<li data-action="click_'+ getData.chatfrom +'" >'+ getData.name + ' message you </li>';
					addnotifi = 1;	
				}
			});
			$('#notifi_desc').html(notificontent);
			notifyCounter();
		});

		var showrequest_friendh = firebase.database().ref().child('friends/'+{{Auth::user()->id}});
		showrequest_friendh.on("value", function (snap) {	
			snap.forEach(function (childsnap) {
				var getData2 = childsnap.val();
				if(getData2.status === 0 && getData2.comefrom != '{{Auth::user()->id}}' ){					
					notificontent += '<li>'+ getData2.name + ' sent you friend request </li>';	
					addnotifi = 1;	
								
				}
			});
			
			$('#notifi_desc').html(notificontent);
			notifyCounter();		
		});
	}
	
	function notifyCounter(){
		var fid = $('.chat-message-wrapper').attr('data-fid');
		var msgidData = fid+"To{{Auth::user()->id}}";
		var updatenotifi = firebase.database().ref().child('chats').orderByChild("msgId").equalTo(msgidData);			
		
		updatenotifi.on("child_added", function(snap) {
			if( $( "#chat" ).hasClass( "chatload" ).toString() == 'true' ){
				var msgViewdata = snap.val();
				
				if (msgViewdata.msgView === '0') { 
					initthefb('chats').child(snap.key+'/').update( {msgView: 1} ); 
				}
			}						
		}); 				
		
		var notifications_count = $('ul#notifi_desc li').length;
		if (notifications_count > 0){
			$('.notify-counter').text(notifications_count);			
			$('.notify-counter').show();			
			$('#notifi_desc').css('padding', '8px 0');	
		} else{
			$('.notify-counter').text('');				
			$('.notify-counter').hide();				
			$('#notifi_desc').css('padding', '0px');				
		} 
	}
</script>