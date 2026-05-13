<div class="loader" style="margin:0 auto;" ng-if="!ctrl.discussionsLoaded"></div>
<h6 ng-if="!(ctrl.discussions | valPresent) && ctrl.discussionsLoaded">There are no discussions available. / No hay discusiones disponibles.</h6>
<table class="table" ng-if="(ctrl.discussions | valPresent) && ctrl.discussionsLoaded">
    <tbody id="postcontentid" class="post_content">
        <tr ng-repeat="discussion in ctrl.discussions | orderBy: ctrl.orderBy track by discussion.id">
            <th>
                <div class="clearfix post-container">
                    <img class="img-responsive profile-image" ng-src="<% discussion.creator.profilePictureUrl %>" alt="<% discussion.creator.name %>"></img>
                    <p style="text-align: justify; font-weight: 500;"><% discussion.discussion %></p>
                    <p class="post-name"><% discussion.creator.name %></p>
                    <span class="post-time" ><% discussion.created_at | date: 'MM-dd-yy hh::mm::ss a' %></span><button class="btn btn-danger pull-right" style="padding: 0 4px; font-size: 12px;" ng-click="ctrl.deleteDiscussion(discussion)" ng-if="discussion.deletable">Delete</button>
                    <div class="comment-container" >
                        <div class="comment-content" ng-repeat="comment in discussion.comments track by comment.id" ng-if="discussion.comments | valPresent">
                            <p>
                                <img class="comment-user-image" ng-src="<% comment.creator.profilePictureUrl %>" alt="<% comment.creator.name %>"></img>
                                <strong><% comment.creator.name %></strong><% comment.content %><span class="comment-time"><% comment.created_at | date: 'MM-dd-yy hh::mm::ss a' %><button class="btn btn-danger pull-right"  style="padding: 0 4px; margin: 0 8px; font-size: 10px;" ng-click="ctrl.deleteDiscussionComment(discussion, comment)" ng-if="comment.deletable">Delete</button></span>
                            </p>
                        </div>
                        <div class="comment-form" >
                            <input type="text" class="input-comment" placeholder="Type your comment" ng-model="new_comment.content">
                            <button class="btn btn-primary pull-right" style="margin-top: 5px;" ng-click="ctrl.createDiscussionComment(discussion, new_comment, {{Auth::user()->id}})" ng-disabled="!(new_comment.content | valPresent)">Post</button>
                        </div>
                    </div>
                </div>
            </th>
        </tr>
    </tbody>
</table>
