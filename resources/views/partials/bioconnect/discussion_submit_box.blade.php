<div class="container pb-cmnt-container">
    <div class="row">
        <div class=" my-4">
            <div class="share_container">
                <h5 class="">Start a Discussion / <em style="font-size:0.85em;color:#16a34a;font-style:italic;">Iniciar una discusión</em></h5>
                <div class="">
                    <form id="share-post" class="form-inline">
                        @csrf
                        <input type="hidden" id="s-post-user-id" name="s-post-created_by" value="{{Auth::user()->id}}" ng-model="discussion.created_by">
                        <textarea id="s-post" name="s-post" ng-model="discussion.discussion" placeholder="Compose your discussion here / Escribe tu discusión aquí" class="pb-cmnt-textarea" required></textarea>
                        <button class="btn btn-primary float-xs-right" style="margin-top: 8px;" ng-click="ctrl.createDiscussion(discussion)" type="button" ng-disabled="!(discussion.discussion | valPresent)">Submit for Discussion / Enviar discusión</button>
                    </form>					
                </div>
            </div>
        </div>
    </div>
</div>
