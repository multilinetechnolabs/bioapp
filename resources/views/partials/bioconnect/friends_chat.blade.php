<script type="text/javascript">
    $( document ).ready( function(){
        $(function() {
            notifyCounter();
            var $newMessage = $('#submit_message');

            var fbinitnotifi = firebase.database().ref().child('chats').orderByChild("chatto").equalTo("{{Auth::user()->id}}");

            fbinitnotifi.on("value", function (snap) {
                var notificontent = '';
                snap.forEach(function (childsnap) {
                    var getData = childsnap.val();
                    if(getData.msgView === '0'){
                        notificontent += '<li data-action="click_'+ getData.chatfrom +'" >'+ getData.name + ' message you </li>';
                    }
                });
                $('#notifi_desc').html(notificontent);
                notifyCounter();
            });

            $('.uk-close').on('click', function () {
                $('#sidebarSecondary').fadeOut(500);
                $('#chat').html('');
                $('*').removeClass('chatload');
            });

            $('#friend_content').on('click', '.show-chat-box', function (ev) {
                ev.preventDefault()

                $('#sidebarSecondary').fadeIn(500);
                $('#chat').addClass('chatload');
                var ths = $(this),
                    chid = ths.data('chatid'),
                    chname = ths.data('chatname'),
                    chProfileImageUrl = ths.data('chatprofileimageurl');

                $('#chat_with').val(chid);

                $('.chatwithname').html(chname);
                $('#sidebarSecondary .chat-box-holder').scrollTop($('.chat-box')[0].scrollHeight);
                //if( $( "#chat" ).hasClass( "chatload" ).toString() == 'true' ){
                loadchats("{{Auth::user()->id}}", $('#chat_with').val());
                //}
                updatenotification("247", $('#chat_with').val());
                setTimeout(function(){
                    notifyCounter();
                }, 300);

            });

            function loadchats(chatfrom, chatto) {

                var iscurrent = '{{Auth::user()->id}}';
                var fbinit = initthefb('chats');

                fbinit.on("value", function (snap) {
                    var chatcontent = '';
                    snap.forEach(function (childsnap) {
                        var childData = childsnap.val();

                        if ((childData.chatfrom === chatfrom && childData.chatto === chatto) || (childData.chatfrom === chatto && childData.chatto === chatfrom) ){
                            var curclass = (childData.chatfrom === chatfrom) ? 'chat-message-right' : '';
                            chatcontent += '<div class="chat-message-wrapper ' + curclass + '"  data-fid="'+ chatto + '" ><div class="chat_user_avatar">' +
                                '<img class="md-user-image" src="public/uploads' + childData['userimg'] + '" alt=""/></div>' +
                                '<ul class="chat-message"><li><p>' +
                                childData['text'] +
                                '</p></li></ul></div>' +
                                '';
                        }

                    });

                    if( $( "#chat" ).hasClass( "chatload" ).toString() == 'true' ){
                        $('#chat').html(chatcontent);
                    }

                    $('#sidebarSecondary .chat-box-holder').scrollTop($('.chat-box')[0].scrollHeight);

                });
            }

            function updatenotification(chatfrom, chatto) {

                var iscurrent = '{{Auth::user()->id}}';
                var doupdate = initthefb('chats');

                doupdate.on("value", function (snap) {
                    var unread_cunt = 0;
                    snap.forEach(function (childsnap) {
                        var childData = childsnap. val();

                        if ((childData.chatfrom === chatfrom && childData.chatto === chatto) || (childData.chatfrom === chatto && childData.chatto === chatfrom) ){
                            updatemsg_status(childsnap.key);
                            unread_cunt++;
                        }

                    });
                    if (unread_cunt > 0){
                        $( "[data-chatid='" + chatto + "']" ).removeClass('has_new_chat');
                    }

                });
            }

            function updatemsg_status(thekey) {
                var doupdate = firebase.database().ref().child('chats/' + thekey);
                doupdate.update({msgStatus:'0'});
            }

            $newMessage.keypress(function (e) {
                // Get field values
                var chid = $('#chat_with').val();
                var fbinit = initthefb('chats');
                var text = $newMessage.val().trim();
                var cdate = "<?=date("Y-m-d H:i:s A")?>";
                var msgID = '{{Auth::user()->id}}' + 'To' + chid;

                if (e.keyCode == 13 && text.length) {
                    fbinit.push({
                        dateTime: cdate,
                        chatto: chid,
                        chatfrom: "{{Auth::user()->id}}",
                        name: "{{Auth::user()->name}}",
                        text: text,
                        userimg: '',
                        msgStatus: '1',
                        msgView: '0',
                        msgId: msgID
                    }, function(error) {
                        if (error) {
                            console.log("Error adding new message:", error);
                            alert(error);
                        }
                    });

                    // Reset new message input
                    $newMessage.val("");
                    $('#sidebarSecondary .chat-box-holder').scrollTop($('.chat-box')[0].scrollHeight);
                    notifyCounter();
                }
            });

            $( "#sendmessage" ).click(function() {

                var chid = $('#chat_with').val();
                var fbinit = initthefb('chats');
                var text = $newMessage.val().trim();
                var cdate = "<?=date("Y-m-d H:i:s A")?>";
                var msgID = '{{Auth::user()->id}}' + 'To' + chid;
                if (text !='') {
                    fbinit.push({
                        dateTime: cdate,
                        chatto: chid,
                        chatfrom: "{{Auth::user()->id}}",
                        name: "{{Auth::user()->name}}",
                        text: text,
                        userimg: '',
                        msgStatus: '1',
                        msgView: '0',
                        msgId: msgID
                    }, function(error) {
                        if (error) {
                            console.log("Error adding new message:", error);
                            alert(error);
                        }
                    });

                    // Reset new message input
                    $newMessage.val("");
                    $('#sidebarSecondary .chat-box-holder').scrollTop($('.chat-box')[0].scrollHeight);
                    notifyCounter();
                }

            });
        });
    });
</script>
