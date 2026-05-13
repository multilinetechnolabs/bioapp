function BioConnectDiscussionsCtrl($scope, $filter, $timeout, Discussion) {
    _this = this

    Discussion.query(function(results) {
        _this.discussionsLoaded = false
        discussions = results

        _this.orderBy = '-created_at'

        if ($("#discussionMode").text() != undefined) {
            _this.discussion_mode = $("#discussionMode").text()
        }

        if ($("#discussionUserId").data('value') != undefined) {
            _this.discussion_user_id = $("#discussionUserId").data('value')
        }

        if (_this.discussion_mode == "Recent Discussions") {
            discussions = $filter('limitTo')(discussions, 50)
        } else if (_this.discussion_mode == "Most Comments") {
            _this.orderBy = '-comments_count'
        } else if (_this.discussion_mode == "My Discussions") {
            discussions = $filter('where')(discussions, { created_by: _this.discussion_user_id })
        } else if (_this.discussion_mode == "My Comments") {
            discussions = $filter('filter')(discussions, { comments: { creator: { id: _this.discussion_user_id } } })
        }

        _this.discussions = discussions

        $timeout( function(){
            if (_this.discussions != undefined) {
                _this.discussionsLoaded = true
            }
        }, 2000)
    })

    this.createDiscussion = function(record) {
        discussion = new Discussion()
        discussion.created_by = record.created_by
        discussion.discussion = record.discussion
        discussion.$save(function(new_discussion){
            _this.discussions.push(new_discussion)
        })
        record.discussion = ''
    }

    this.deleteDiscussion = function(record) {
        var confirmDialog = confirm("Are you sure you wish to remove this discussion?");
        if (confirmDialog == true) {
            _this.discussionsLoaded = false
            record.$delete(function(){
                index = _this.discussions.indexOf(record)
                _this.discussions.splice(index, 1)
                _this.discussionsLoaded = true
            })
        }
    }

    this.createDiscussionComment = function(discussion, new_comment, user_id) {
        _this.discussionsLoaded = false
        comment = new Discussion.prototype.Comment({ discussion_id: discussion.id })
        comment.content = new_comment.content
        comment.user_id = user_id
        comment.$save(function(comment){
            discussion.comments.push(comment)
            new_comment.content = ''
            _this.discussionsLoaded = true
        })
    }

    this.deleteDiscussionComment  = function(discussion, comment) {
        var confirmDialog = confirm("Are you sure you wish to remove this comment?");
        if (confirmDialog == true) {
            Discussion.prototype.Comment.delete({ discussion_id: discussion.id, id: comment.id }, function(comment) {
                comments = discussion.comments
                index = comments.indexOf(comment)
                comments.splice(index, 1)
            })
        }
    }
}
BioConnectDiscussionsCtrl.$inject = ['$scope', '$filter', '$timeout', 'Discussion'];

angular.module('AnewApp').controller('BioConnectDiscussionsCtrl', BioConnectDiscussionsCtrl);
