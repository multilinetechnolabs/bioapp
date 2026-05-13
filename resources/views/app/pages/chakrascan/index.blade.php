@extends('layouts.modern')

@php
    $activeNav = 'chakra';
    $useAppShell = true;
@endphp

@section('page-title', 'Chakra Scan')

@section('content')
    @php($target = request()->target ?? 'female')

    <main class="modern-main-content modern-main-content--fluid">
        <div class="container-fluid modern-bodyscan-wrap">
            <div class="modern-page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="eyebrow">Biomagnetism / Biomagnetismo</span>
                    <h1 class="modern-page-title mb-0">Chakra Scan / Escaneo Chakra</h1>
                    <p class="modern-page-subtitle mb-0">Interactive 3D model and chakra scan list / Modelo 3D interactivo y lista de escaneo chakra</p>
                </div>
                <button type="button" class="modern-howto-btn" data-toggle="modal" data-target="#howToScanModal">
                    <img src="{{ asset('/images/iconimages/humanicon48.png') }}" alt="" class="howto-icon">
                    <span>How to Scan / Cómo Escanear</span>
                </button>
            </div>

            <div style="display: none;" id="scanType" data-value="chakra_scan"></div>
            <div style="display: none;" id="modelTarget" data-value="{{ $target }}"></div>
            <div style="display: none;" id="userIsAdmin"
                data-value="{{ Auth::user()->isAdmin() ? 'administrator' : '' }}"></div>
            <div style="display: none;" id="ssid" data-value="{{ $request->ssid }}"></div>
            <div style="display: none;" id="guided" data-value="{{ $request->guided ?? 'false' }}"></div>
            <div style="display: none;" id="page" data-value="{{ $request->page ?? 1 }}"></div>
            <div style="display: none;" id="perPage" data-value="{{ $request->perPage ?? 5 }}"></div>

            <div class="modern-scan-shell" ng-controller="ModelLabelsCtrl as ctrl">
                <div class="loader" style="margin:0 auto; margin-top: 100px;"
                    ng-if="!(ctrl.search.meta.loaded | valPresent) || !ctrl.search.meta.loaded"></div>
                <div class="row g-4 align-items-start signup-form-row" ng-hide="!ctrl.search.meta.loaded">

                    {{-- LEFT: 3D model card --}}
                    <div class="col-12 col-lg-6 col-xl-6 modern-scan-model-col">
                        <div class="modern-scan-card modern-scan-card--model">
                            <div id="modelcontainer" style="display: none;"></div>
                            <div id="toggleModel" class="scan-toggle-model" style="display: none;">
                                @if ($target == 'male')
                                    <a href="/chakrascan?target=female&guided=<% ctrl.guided_scan %>"
                                        class="modern-toolbar-link">
                                        <button type="button" class="modern-btn modern-btn--outline">
                                            Switch to Female / Cambiar a femenino
                                        </button>
                                    </a>
                                @else
                                    <a href="/chakrascan?target=male&guided=<% ctrl.guided_scan %>"
                                        class="modern-toolbar-link">
                                        <button type="button" class="modern-btn modern-btn--outline">
                                            Switch to Male / Cambiar a masculino
                                        </button>
                                    </a>
                                @endif
                                <span class="modern-control-group">
                                    <button type="button" id="zoomIn" class="modern-btn modern-btn--ghost"
                                        ng-click="ctrl.zoomIn($event)">
                                        Zoom In / Acercar
                                    </button>
                                    <button type="button" id="zoomOut" class="modern-btn modern-btn--ghost"
                                        ng-click="ctrl.zoomOut($event)">
                                        Zoom Out / Alejar
                                    </button>
                                </span>
                                <span class="modern-control-group">
                                    <button type="button" id="prevPoint" class="modern-btn modern-btn--ghost"
                                        ng-click="ctrl.prevPoint($event)">
                                        Previous / Anterior
                                    </button>
                                    <button type="button" id="nextPoint" class="modern-btn modern-btn--ghost"
                                        ng-click="ctrl.nextPoint($event)">
                                        Next / Siguiente
                                    </button>
                                </span>
                                @if (Auth::user()->isAdmin())
                                    <span class="modern-control-group">
                                        <button type="button" id="addPoint" class="modern-btn modern-btn--primary"
                                            data-toggle="modal" data-target="#modelLabelModal"
                                            data-title="Add Model Label / Agregar etiqueta del modelo"
                                            ng-if="!(ctrl.client | valPresent)" disabled>Add / Agregar</button>
                                        <span id="pointX" data-value="0" style="display: none;">Point X: 0</span>
                                        <span id="pointY" data-value="0" style="display: none;">Point Y: 0</span>
                                        <span id="pointZ" data-value="0" style="display: none;">Point Z: 0</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: data card --}}
                    <div class="col-12 col-lg-6 col-xl-6 modern-scan-data-col">
                        <div class="modern-scan-card scan-point-container" ng-cloak="">
                            <h6 style="font-size: 28px; text-align: center;"
                                ng-if="(ctrl.model_labels.length > 0) && ctrl.disable_client_selection"><a
                                    href="/data_cache/clients/<% ctrl.client.id %>" target="_blank"><% ctrl.client.name %></a>
                            </h6>

                            <div class="row scan-search-box" ng-if="ctrl.model_labels.length > 0">
                                <img src="{{ asset('/images/scan-search-icon.png') }}" alt="{{ env('APP_TITLE') }}"></img>
                                <form>
                                    <input type="text" placeholder="Search... / Buscar..."
                                        ng-model="ctrl.search.params.text" style="width: calc(100% - 66px)"></input>
                                    <select ng-model="ctrl.search.meta.perPage" style="width: 62px; height: 28px;">
                                        <option ng-repeat="itemsPerPage in ctrl.search.perPageOptions"
                                            ng-value="itemsPerPage">
                                            <% itemsPerPage %></option>
                                    </select>
                                </form>
                            </div>

                            <div class="modern-scan-toolbar">
                                @if ($request->guided == 'true')
                                    <a href="/chakrascan?target=<% ctrl.search.params.target %>&guided=false"
                                        class="modern-toolbar-link">
                                        <button type="button" class="modern-btn modern-btn--outline">
                                            Switch to Non Guided Scan / Cambiar a escaneo no guiado
                                        </button>
                                    </a>
                                @else
                                    <a href="/chakrascan?target=<% ctrl.search.params.target %>&guided=true"
                                        class="modern-toolbar-link">
                                        <button type="button" class="modern-btn modern-btn--outline">
                                            Switch to Guided Scan / Cambiar a escaneo guiado
                                        </button>
                                    </a>
                                @endif
                                <button type="button" class="modern-btn modern-btn--outline modern-toolbar-bookmark"
                                    ng-click="ctrl.showBookmark = (ctrl.showBookmark ? false : true)">
                                    <% ctrl.showBookmark ? 'Hide Bookmarks / Ocultar marcadores' : 'Show Bookmarks / Mostrar marcadores' %>
                                </button>
                            </div>

                            <div class="modern-bookmark-panel" ng-if="ctrl.showBookmark">
                                <div class="modern-bookmark-editor">
                                    <input type="text" class="modern-bookmark-input" ng-model="bookmark_record.name"
                                        placeholder="Bookmark Title / Título del marcador">
                                    <button type="button" class="modern-btn modern-btn--primary"
                                        ng-click="ctrl.saveBookmark(bookmark_record)"
                                        ng-disabled="!(bookmark_record.name | valPresent)">Save / Guardar</button>
                                    <button type="button" class="modern-btn modern-btn--ghost"
                                        ng-click="ctrl.clearBookmark()"
                                        ng-if="(bookmark_record.id | valPresent)">Cancel / Cancelar</button>
                                </div>
                                <div class="modern-bookmark-empty" ng-if="!(ctrl.bookmarks | valPresent)">
                                    You have no saved bookmarks. / No tiene marcadores guardados.
                                </div>
                                <table class="modern-bookmark-table" ng-if="ctrl.bookmarks | valPresent">
                                    <thead>
                                        <tr>
                                            <th>Name / Nombre</th>
                                            <th>Created At / Creado el</th>
                                            <th class="text-end" style="width: 160px;">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="bookmark in ctrl.bookmarks track by bookmark.id">
                                            <td><a href="<% bookmark.url %>"><% bookmark.name %></a></td>
                                            <td><% bookmark.created_at %></td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="modern-btn modern-btn--small modern-btn--outline"
                                                    ng-if="bookmark.editable" ng-click="ctrl.editBookmark(bookmark)"
                                                    ng-disabled="(bookmark_record.id | valPresent)">Edit / Editar</button>
                                                <button type="button"
                                                    class="modern-btn modern-btn--small modern-btn--danger"
                                                    ng-if="bookmark.deletable"
                                                    ng-click="ctrl.deleteBookmark(bookmark)"
                                                    ng-disabled="(bookmark_record.id | valPresent)">Delete / Eliminar</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="modern-client-row"
                                ng-if="(ctrl.model_labels.length > 0) && !ctrl.disable_client_selection">
                                <label class="modern-client-label" ng-if="ctrl.clients | valPresent">Client /
                                    Cliente:</label>
                                <select class="form-control modern-client-select" id="client_id" name="client_id"
                                    ng-model="ctrl.client" ng-if="ctrl.clients | valPresent">
                                    <option ng-value="null">(No client selected / Ningún cliente seleccionado)</option>
                                    <option
                                        ng-repeat="client in ctrl.clients | where: { gender: ctrl.search.params.target, user_id: {{ Auth::user()->id }} } track by client.id"
                                        ng-value="<% client %>"><% client.name %></option>
                                </select>
                                <a href="/data_cache/clients/<% ctrl.client.id %>?scan_type=chakra_scan" target="_blank"
                                    class="modern-toolbar-link">
                                    <button type="button" class="modern-btn modern-btn--primary btn-data-cache"
                                        ng-disabled="!(ctrl.client | valPresent)"
                                        ng-if="ctrl.clients | valPresent">View / Ver</button>
                                </a>
                                <a href="/data_cache/client_info" target="_blank" class="modern-toolbar-link">
                                    <button type="button" class="modern-btn modern-btn--primary btn-data-cache"
                                        ng-disabled="ctrl.client | valPresent"
                                        ng-if="ctrl.clients | valPresent">Add / Agregar</button>
                                </a>
                            </div>

                            <div class="text-center">
                                <span style="font-size: 16.5px;"
                                    ng-if="(ctrl.client | valPresent) && !(ctrl.client.scan_session_id | valPresent)">No
                                    active scan session for this client. / No hay una sesión de escaneo activa para este
                                    cliente.
                                    <a href="/data_cache/clients/<% ctrl.client.id %>">Click here to create a scan
                                        session. / Haga clic aquí para crear una sesión de escaneo.</a>
                                </span>
                            </div>

                            <h5 ng-if="ctrl.model_labels.length > 0" style="margin: 8px 0;">
                                <% ctrl.search.meta.pageStatus %>
                            </h5>

                            <div class="row second-layer">
                                <span style="margin-left: 50px; margin-top: 10px;"
                                    ng-if="(ctrl.model_labels | filter: { point: { name: ctrl.search.params.text } }).length == 0">No
                                    results found. / No se encontraron resultados.</span>
                                <ul id="accordion" ng-if="ctrl.model_labels.length > 0" style="width: 100%;">
                                    <li
                                        ng-repeat="model_label in ctrl.model_labels | orderBy: ctrl.orderBy | filter: { point: { name: ctrl.search.params.text } } | unique: 'point.name' | slice: ctrl.search.meta.startPos:ctrl.search.meta.startPos+ctrl.search.meta.perPage">
                                        <button class="btn btn-link collapsed"
                                            ng-click="ctrl.addPairToClient(model_label.point)"
                                            ng-if="!model_label.point._delete && (ctrl.client | valPresent) && (ctrl.client.scan_session_id | valPresent)">
                                            <img src="{{ asset('/images/scan-btn-add.png') }}"
                                                alt="{{ env('APP_TITLE') }}"></img>
                                        </button>
                                        <button class="btn btn-link collapsed"
                                            ng-click="ctrl.removePairToClient(model_label.point)"
                                            ng-if="model_label.point._delete && (ctrl.client | valPresent) && (ctrl.client.scan_session_id | valPresent)">
                                            <img src="{{ asset('/images/scan-btn-minus.png') }}"
                                                alt="{{ env('APP_TITLE') }}"></img>
                                        </button>
                                        <span class="btn-link" data-toggle="collapse"
                                            data-target="#collapse-<% model_label.id %>" aria-expanded="true"
                                            aria-controls="collapse-<% model_label.id %>"
                                            ng-click="ctrl.showPoints($event, model_label.id)"><% ctrl.getPairName(model_label.point) || '-' %></span>
                                        <div id="collapse-<% model_label.id %>" class="collapse"
                                            aria-labelledby="heading-<% model_label.id %>" data-parent="#accordion"
                                            style="font-size: 16px;">
                                            <table style="margin-left: 50px; margin-top: 10px; width: calc(100% - 50px);"
                                                border="1">
                                                <thead></thead>
                                                <tbody>
                                                    @if (Auth::user()->isAdmin())
                                                        <tr ng-repeat="label in ctrl.model_labels | orderBy: ctrl.orderBy | filter: { point: { id: model_label.point.id, name: model_label.point.name } } track by label.id"
                                                            ng-if="!(ctrl.client | valPresent)">
                                                            <td colspan="2">
                                                                <span>
                                                                    X: <input type="text" ng-value="label.point_x"
                                                                        disabled=""
                                                                        style="width: 60px; max-width: 120px;">
                                                                    Y: <input type="text" ng-value="label.point_y"
                                                                        disabled=""
                                                                        style="width: 60px; max-width: 120px;">
                                                                    Z: <input type="text" ng-value="label.point_z"
                                                                        disabled=""
                                                                        style="width: 60px; max-width: 120px;">
                                                                </span>
                                                                <div class="modern-admin-actions">
                                                                    <button type="button"
                                                                        class="modern-btn modern-btn--small modern-btn--ghost get-coordinates"
                                                                        ng-click="ctrl.getCoordinates($event, label.id)">
                                                                        Get Coordinates / Obtener coordenadas
                                                                    </button>
                                                                    <button type="button"
                                                                        class="modern-btn modern-btn--small modern-btn--outline editor-edit"
                                                                        data-toggle="modal"
                                                                        data-target="#modelLabelModal"
                                                                        data-title="Edit Model Label / Editar etiqueta del modelo"
                                                                        data-id="<% label.id %>">Edit / Editar</button>
                                                                    <button type="button"
                                                                        class="modern-btn modern-btn--small modern-btn--danger editor-remove"
                                                                        ng-click="ctrl.deletePair($event, label.id)">
                                                                        Delete / Eliminar
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td>Points/Name / Puntos/Nombre:</td>
                                                        <td><% model_label.point.name || '-' %></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Radical / Radical:</td>
                                                        <td><% model_label.point.radical || '-' %></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Start/Origin / Inicio/Origen:</td>
                                                        <td><% model_label.point.origins || '-' %></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Leads/Symptoms / Señales/Síntomas:</td>
                                                        <td><% model_label.point.symptoms || '-' %></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Path/Route/Cause and Effect / Ruta/Causa y efecto:</td>
                                                        <td><% model_label.point.paths || '-' %></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alternative Routes / Rutas alternativas:</td>
                                                        <td><% model_label.point.alternative_routes || '-' %></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- How To Scan Modal -->
    <div class="modal fade" id="howToScanModal" tabindex="-1" role="dialog" aria-labelledby="howToScanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #f5f5f5;">
                    <div style="text-align: center; width: 100%;">
                        <h5 class="modal-title" id="howToScanModalLabel" style="font-weight: bold; margin-bottom: 4px;">
                            How Chakra Scan Works</h5>
                        <p style="margin: 0; font-size: 13px;">There are 289 chakra pairs in total, including: 7 major
                            chakras, 21 minor chakras, 86 micro chakras.</p>
                        <p style="margin: 0; font-size: 12px;">This scan uses biomagnetic placement and biofeedback to
                            assess and support energetic balance.</p>
                        <p style="margin: 4px 0 0; font-size: 11px; color: #666;">Hay 289 pares de chakras en total,
                            incluyendo: 7 chakras mayores, 21 chakras menores, 86 micro chakras.<br>Este escaneo utiliza la
                            colocaci&oacute;n biom&aacute;gnetica y la biorretroalimentaci&oacute;n para evaluar y apoyar el
                            equilibrio energ&eacute;tico.</p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="position: absolute; right: 16px; top: 12px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 13px; max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>1. Follow the Chakra Scan Order</strong></p>
                            <p>Begin at the Root Chakra and move upward in this sequence:<br>
                                Root &rarr; Sacral &rarr; Solar Plexus &rarr; Heart &rarr; Throat &rarr; Third Eye &rarr;
                                Crown<br>
                                Proceed step-by-step using the instructions below.</p>
                            <p style="color:#555;"><em>Comience en el Chakra Ra&iacute;z y avance hacia arriba en esta
                                    secuencia:<br>
                                    Ra&iacute;z &rarr; Sacro &rarr; Plexo Solar &rarr; Coraz&oacute;n &rarr; Garganta &rarr;
                                    Tercer Ojo &rarr; Corona<br>
                                    Contin&uacute;e paso a paso siguiendo las instrucciones.</em></p>

                            <p><strong>2. Check Each Major Chakra</strong></p>
                            <p>Place the negative magnet on the front of the chakra.<br>
                                Observe the body&rsquo;s biofeedback response. You may monitor:<br>
                                &nbsp;&nbsp;Right leg length &nbsp;|&nbsp; Hands or feet response &nbsp;|&nbsp; Body sway
                                or subtle movement<br><br>
                                <strong>Shortening (contraction):</strong> &rarr; Place positive magnet on the back and
                                recheck<br>
                                <strong>Lengthening:</strong> &rarr; Reverse polarity (positive front / negative back) and
                                recheck<br>
                                <strong>No change:</strong> &rarr; Indicates a balanced response
                            </p>
                            <p style="color:#555;"><em>Coloque el im&aacute;n negativo en la parte frontal del chakra.<br>
                                    Observe la respuesta de biorretroalimentaci&oacute;n del cuerpo. Puede observar:<br>
                                    &nbsp;&nbsp;Longitud de la pierna derecha &nbsp;|&nbsp; Respuesta de manos o pies
                                    &nbsp;|&nbsp; Balanceo corporal o movimiento sutil<br><br>
                                    Acortamiento (contracci&oacute;n): &rarr; Coloque el im&aacute;n positivo en la parte
                                    posterior y vuelva a verificar<br>
                                    Alargamiento: &rarr; Invierta la polaridad (positivo al frente / negativo atr&aacute;s)
                                    y vuelva a verificar<br>
                                    Sin cambio: &rarr; Indica una respuesta equilibrada</em></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>3. Magnets Remain on All Major Chakras</strong></p>
                            <p>After each major chakra is checked, magnets remain in place on every major chakra.<br>
                                Even when a chakra appears balanced, the magnets stay applied.<br>
                                Standard placement is negative in front / positive in back.<br>
                                By the end of the scan, all 7 major chakras will have magnets in place.<br>
                                This supports consistent energetic flow throughout the full scan.</p>
                            <p style="color:#555;"><em>Despu&eacute;s de verificar cada chakra mayor, los imanes permanecen
                                    colocados en cada chakra mayor.<br>
                                    Incluso cuando un chakra parece equilibrado, los imanes permanecen colocados.<br>
                                    La colocaci&oacute;n est&aacute;ndar es negativo al frente / positivo atr&aacute;s.<br>
                                    Al final del escaneo, los 7 chakras mayores tendr&aacute;n imanes colocados.<br>
                                    Esto apoya un flujo energ&eacute;tico constante durante todo el escaneo.</em></p>

                            <p><strong>4. Follow the Guided Scan for Each Chakra</strong></p>
                            <p>Within the guided scan, each major chakra is followed by its related minor and micro
                                areas.<br>
                                If a major chakra shows imbalance, continue through the areas listed beneath it.<br>
                                Work through the listed areas until the next major chakra appears, then continue upward.
                            </p>
                            <p style="color:#555;"><em>Dentro del escaneo guiado, cada chakra mayor es seguido por sus
                                    &aacute;reas relacionadas menores y micro.<br>
                                    Si un chakra mayor muestra desequilibrio, contin&uacute;e con las &aacute;reas listadas
                                    debajo.<br>
                                    Trabaje a trav&eacute;s de las &aacute;reas listadas hasta que aparezca el siguiente
                                    chakra mayor, luego contin&uacute;e hacia arriba.</em></p>

                            <p><strong>5. Completion &amp; Timing</strong></p>
                            <p>After all chakras have been scanned and balanced, leave magnets in place for approximately 15
                                minutes in most areas.<br>
                                This follows biomagnetic principles, supporting balance through a hemispheric-to-equatorial
                                magnetic response.<br>
                                Timing may vary slightly, though 15 minutes is commonly used.</p>
                            <p style="color:#555;"><em>Despu&eacute;s de que todos los chakras hayan sido escaneados y
                                    equilibrados, deje los imanes colocados durante aproximadamente 15 minutos en la
                                    mayor&iacute;a de las &aacute;reas.<br>
                                    Esto sigue principios biom&aacute;gneticos, apoyando el equilibrio a trav&eacute;s de
                                    una respuesta magn&eacute;tica de hemisferio a ecuador.<br>
                                    El tiempo puede variar ligeramente, aunque 15 minutos es lo m&aacute;s
                                    com&uacute;n.</em></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Model Label Modal -->
    <div class="modal fade" id="modelLabelModal" tabindex="-1" role="dialog" aria-labelledby="modelLabelModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelLabelModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="model_label_id" name="model_label_id">
                        <input type="hidden" id="scan_type" name="scan_type">
                        <input type="hidden" id="target" name="target">
                        <div class="form-group">
                            <label for="label">Point / Punto</label>
                            <select class="form-control" id="pair_id" name="pair_id" required>
                                @foreach (\App\Models\Pair::orderBy('name', 'asc')->where('scan_type', '=', 'chakra_scan')->get() as $pair)
                                    <option value="{{ $pair->id }}">{{ $pair->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label>Point Coordinates / Coordenadas del punto</label>
                        <div class="form-group row">
                            <div class="col">
                                <label>X</label>
                                <input type="number" class="form-control" id="point_x" name="point_x" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary save-btn">Save</button>
                            </div>
                            <div class="col">
                                <label>Z</label>
                                <input type="number" class="form-control" id="point_z" name="point_z" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close / Cerrar</button>
                        <button type="button" class="btn btn-primary save-btn">Save / Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#modelLabelModal').on('show.bs.modal', function(e) {
            var trigger = $(e.relatedTarget)
            $('#modelLabelModalTitle').text(trigger.data('title'));

            $model_label_id = trigger.data('id')
            if ($model_label_id != null) {
                $.ajax({
                    url: '{{ env('APP_WEB_API_URL') }}/{{ env('APP_WEB_API_VERSION') }}/model_labels/' +
                        $model_label_id,
                    type: 'GET',
                    success: function(result) {
                        $('#model_label_id').val(result.id);
                        $('#scan_type').val(result.scan_type);
                        $('#target').val(result.target);
                        $('#pair_id').val(result.pair_id);
                        $('#point_x').val(result.point_x);
                        $('#point_y').val(result.point_y);
                        $('#point_z').val(result.point_z);
                    }
                });
            } else {
                $('#model_label_id').val('');
                $('#target').val($('#modelTarget').data('value'));
                $('#scan_type').val($('#scanType').data('value'));
                $('#pair_id').val(null);
                $('#point_x').val($('#pointX').data('value') || 0);
                $('#point_y').val($('#pointY').data('value') || 0);
                $('#point_z').val($('#pointZ').data('value') || 0);
            }
        })

        $(".save-btn").click(function(e) {
            e.preventDefault();

            var $model_label_id = $('#model_label_id').val()

            var data = {
                target: $("#target").val(),
                scan_type: $("#scan_type").val(),
                pair_id: $("#pair_id").val(),
                point_x: $("#point_x").val(),
                point_y: $("#point_y").val(),
                point_z: $("#point_z").val(),
            };

            if ($("#target").val() == '' || $("#target").val() == null) {
                alert('Target must be filled out.')
                $("#target").focus()
            } else if ($("#scan_type").val() == '' || $("#scan_type").val() == null) {
                alert('Scan type must be filled out.')
                $("#scan_type").focus()
            } else if ($("#pair_id").val() == '' || $("#pair_id").val() == null) {
                alert('Point must be filled out.')
                $("#pair_id").focus()
            } else if ($("#point_x").val() == '' || $("#point_x").val() == null) {
                alert('Point X must be filled out.')
                $("#point_x").focus()
            } else if ($("#point_y").val() == '' || $("#point_y").val() == null) {
                alert('Point Y must be filled out.')
                $("#point_y").focus()
            } else if ($("#point_z").val() == '' || $("#point_z").val() == null) {
                alert('Point Z must be filled out.')
                $("#point_z").focus()
            } else {
                if ($model_label_id != undefined && $model_label_id != '') {
                    $.ajax({
                        url: '{{ env('APP_WEB_API_URL') }}/{{ env('APP_WEB_API_VERSION') }}/model_labels/' +
                            $model_label_id,
                        type: 'PUT',
                        data: data,
                        dataType: 'JSON',
                        success: function(data) {
                            location.reload();
                        }
                    });
                } else {
                    $.ajax({
                        url: '{{ env('APP_WEB_API_URL') }}/{{ env('APP_WEB_API_VERSION') }}/model_labels',
                        type: 'POST',
                        data: data,
                        dataType: 'JSON',
                        success: function(data) {
                            location.reload();
                        }
                    });
                }
            }
        });
    </script>
@endpush
