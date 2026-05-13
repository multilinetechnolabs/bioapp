<!-- Preferences Modal -->
<div class="modal fade" id="preferencesModal" tabindex="-1" role="dialog" aria-labelledby="preferencesModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #000;">Preferences / Preferencias</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('app.pages.data_cache.partials.preferences_content')
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
</div>
<!-- End of Preferences Modal -->
